<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * GET /api/stats
     * Returns: mrr, total_clients, paid, pending, late
     */
    public function index(): JsonResponse
    {
        $now = Carbon::now();

        $mrr = Payment::where('status_payment', 'payé')
            ->whereMonth('date_payment', $now->month)
            ->whereYear('date_payment', $now->year)
            ->sum('montant');

        return response()->json([
            'data' => [
                'mrr'           => (float) $mrr,
                'total_clients' => Client::count(),
                'paid'          => Client::where('statut_paiement', 'payé')->count(),
                'pending'       => Client::where('statut_paiement', 'en_attente')->count(),
                'late'          => Client::where('statut_paiement', 'en_retard')->count(),
            ],
        ]);
    }

    /**
     * GET /api/stats/revenue?year=2025
     * Returns monthly revenue array (12 values) for Chart.js
     */
    public function revenue(Request $request): JsonResponse
    {
        $year = $request->integer('year', now()->year);

        $results = Payment::selectRaw('MONTH(date_payment) as month, SUM(montant) as total')
            ->where('status_payment', 'payé')
            ->whereYear('date_payment', $year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $monthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthly[] = isset($results[$m]) ? (float) $results[$m]->total : 0.0;
        }

        $availableYears = Payment::selectRaw('DISTINCT YEAR(date_payment) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return response()->json([
            'data' => [
                'year'            => $year,
                'monthly_revenue' => $monthly,
                'available_years' => $availableYears,
            ],
        ]);
    }
}
