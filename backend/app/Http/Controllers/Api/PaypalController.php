<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\LicenseOffer;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    /**
     * POST /api/boutique/checkout
     * Body: { offer_id: number }
     * Returns: { approval_url: string }
     */
    public function checkout(Request $request): JsonResponse
    {
        $request->validate(['offer_id' => 'required|exists:license_offers,id']);

        $offer = LicenseOffer::findOrFail($request->offer_id);

        if ($offer->quantite_disponible <= 0) {
            return response()->json(['message' => 'Cette offre n\'est plus disponible.'], 422);
        }

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();

            if (empty($token['access_token'])) {
                return response()->json(['message' => 'Configuration PayPal invalide.'], 500);
            }

            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

            $response = $provider->createOrder([
                'intent'              => 'CAPTURE',
                'application_context' => [
                    'return_url' => $frontendUrl.'/boutique/success',
                    'cancel_url' => $frontendUrl.'/boutique/cancel',
                ],
                'purchase_units' => [[
                    'reference_id' => (string) $offer->id,
                    'description'  => $offer->nom,
                    'amount'       => [
                        'currency_code' => config('paypal.currency', 'USD'),
                        'value'         => number_format((float) $offer->prix, 2, '.', ''),
                    ],
                ]],
            ]);

            if (isset($response['id'])) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return response()->json([
                            'data' => [
                                'order_id'     => $response['id'],
                                'approval_url' => $link['href'],
                                'offer_id'     => $offer->id,
                            ],
                        ]);
                    }
                }
            }

            return response()->json(['message' => $response['message'] ?? 'Erreur PayPal.'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur de connexion à PayPal: '.$e->getMessage()], 500);
        }
    }

    /**
     * GET /api/boutique/success?token=&offer_id=
     * Called after PayPal redirect — captures the order.
     */
    public function success(Request $request): JsonResponse
    {
        $request->validate([
            'token'    => 'required|string',
            'offer_id' => 'required|exists:license_offers,id',
        ]);

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->capturePaymentOrder($request->token);

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                $offer  = LicenseOffer::findOrFail($request->offer_id);
                $client = $request->user()->client;

                if ($offer && $client) {
                    $offer->decrement('quantite_disponible');

                    License::create([
                        'nom'                 => $offer->nom,
                        'quantite_disponible' => 1,
                        'client_id'           => $client->id,
                        'date_assignation'    => now(),
                    ]);

                    Payment::create([
                        'montant'        => $offer->prix,
                        'date_payment'   => now(),
                        'status_payment' => 'payé',
                        'client_id'      => $client->id,
                    ]);
                }

                return response()->json(['message' => 'Paiement réussi. Votre licence a été assignée.']);
            }

            return response()->json(['message' => 'Le paiement n\'a pas pu être complété.'], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la capture: '.$e->getMessage()], 500);
        }
    }

    /**
     * GET /api/boutique/cancel
     */
    public function cancel(): JsonResponse
    {
        return response()->json(['message' => 'Paiement annulé.'], 200);
    }
}
