<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MonEspaceController extends Controller
{
    /**
     * GET /api/mon-espace
     * Returns the authenticated client's own payments + licenses.
     */
    public function index(Request $request): JsonResponse
    {
        $user   = $request->user();
        $client = $user->client;

        if (! $client) {
            return response()->json(['message' => 'Profil client introuvable.'], 404);
        }

        $client->load([
            'payments' => fn ($q) => $q->orderBy('date_payment', 'desc'),
            'license'  => fn ($q) => $q->orderBy('date_assignation', 'desc'),
        ]);

        return response()->json([
            'data' => [
                'client'   => [
                    'id'               => $client->id,
                    'nom'              => $client->nom,
                    'email'            => $client->email,
                    'telephone'        => $client->telephone,
                    'statut_paiement'  => $client->statut_paiement,
                    'date_maintenance' => $client->date_maintenance,
                    'licences_count'   => $client->licences_count,
                    'relance_flag'     => $client->relance_flag,
                ],
                'payments' => $client->payments,
                'licenses' => $client->license,
            ],
        ]);
    }

    /**
     * GET /api/mon-espace/export/pdf
     */
    public function exportPdf(Request $request): Response
    {
        $client = $request->user()->client;

        if (! $client) {
            abort(404, 'Profil client introuvable.');
        }

        $payments = $client->payments()->orderBy('date_payment', 'desc')->get();
        $filename = 'mes-paiements-'.now()->format('Y-m-d').'.pdf';

        $pdf = Pdf::loadView('pdf.payments', [
            'client'   => $client,
            'payments' => $payments,
        ])->setPaper('a4', 'portrait');

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
