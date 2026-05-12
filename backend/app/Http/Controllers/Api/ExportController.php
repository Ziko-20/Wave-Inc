<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * GET /api/clients/export/csv
     */
    public function exportClientsCsv(): StreamedResponse
    {
        $clients = Client::with('payments')->orderBy('nom')->get();
        $filename = 'clients-'.now()->format('Y-m-d').'.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($clients) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, ['ID', 'Nom', 'Email', 'Téléphone', 'Statut', 'Date Maintenance', 'Nb Licences', 'Relance', 'Date Relance', 'Note Relance'], ';');

            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->nom,
                    $client->email,
                    $client->telephone,
                    $client->statut_paiement,
                    $client->date_maintenance,
                    $client->licences_count,
                    $client->relance_flag ? 'Oui' : 'Non',
                    $client->date_relance,
                    $client->note_relance,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * GET /api/clients/{client}/export/pdf
     */
    public function exportClientPdf(Client $client): Response
    {
        $client->load(['payments' => fn ($q) => $q->orderBy('date_payment', 'desc'), 'license']);

        $pdf = Pdf::loadView('pdf.client', [
            'client'   => $client,
            'payments' => $client->payments,
            'licenses' => $client->license,
        ])->setPaper('a4', 'portrait');

        $filename = 'client-'.$client->nom.'-'.now()->format('Y-m-d').'.pdf';

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
