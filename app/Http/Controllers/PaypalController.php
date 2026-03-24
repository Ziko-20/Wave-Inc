<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\LicenseOffer;
use App\Models\License;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaypalController extends Controller
{
    public function createPayment(LicenseOffer $offer)
    {
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();

            if (empty($paypalToken['access_token'])) {
                return redirect()->route('license.shop')->with('error', 'Configuration PayPal invalide ou identifiants manquants.');
            }

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.capture'),
                    "cancel_url" => route('paypal.cancel'),
                ],
                "purchase_units" => [
                    0 => [
                        "reference_id" => $offer->id,
                        "amount" => [
                            "currency_code" => "USD", // PayPal typically tests with USD
                            "value" => $offer->prix
                        ],
                        "description" => $offer->nom
                    ]
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        // Store the offer ID in session to assign it later
                        session()->put('purchasing_offer_id', $offer->id);
                        return redirect()->away($links['href']);
                    }
                }
                return redirect()->route('license.shop')->with('error', 'Une erreur s\'est produite avec le paiement via PayPal.');
            } else {
                return redirect()->route('license.shop')->with('error', $response['message'] ?? 'Une erreur s\'est produite avec l\'API PayPal.');
            }
        } catch (\Exception $e) {
            return redirect()->route('license.shop')->with('error', 'Erreur de connexion à PayPal: Vérifiez vos clés secrètes dans le .env');
        }
    }

    public function capturePayment(Request $request)
    {
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->capturePaymentOrder($request['token']);

            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                $offerId = session()->get('purchasing_offer_id');
                $offer = LicenseOffer::find($offerId);
                $client = Auth::user()->client;

                if ($offer && $client) {
                    // Decrement stock
                    $offer->decrement('quantite_disponible');

                    // Create License for client
                    License::create([
                        'nom' => $offer->nom,
                        'quantite_disponible' => 1,
                        'client_id' => $client->id,
                        'date_assignation' => now()
                    ]);

                    // Create Payment history record
                    Payment::create([
                        'montant' => $offer->prix,
                        'date_payment' => now(),
                        'status_payment' => 'payé',
                        'client_id' => $client->id
                    ]);
                }

                session()->forget('purchasing_offer_id');
                return redirect()->route('license.shop')->with('success', 'Paiement réussi ! Votre licence a été assignée.');
            } else {
                return redirect()->route('license.shop')->with('error', 'Le paiement n\'a pas pu être complété.');
            }
        } catch (\Exception $e) {
            return redirect()->route('license.shop')->with('error', 'Erreur lors de la capture du paiement avec PayPal.');
        }
    }

    public function cancelPayment()
    {
        return redirect()->route('license.shop')->with('error', 'Le paiement a été annulé.');
    }
}
