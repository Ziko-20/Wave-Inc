<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiements — {{ $client->nom }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a2e; }
        h1 { color: #22419A; font-size: 20px; margin-bottom: 4px; }
        .subtitle { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #22419A; color: white; padding: 8px 10px; text-align: left; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) td { background: #f9fafb; }
        .badge-paye    { color: #059669; font-weight: bold; }
        .badge-attente { color: #d97706; font-weight: bold; }
        .badge-retard  { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <h1>Historique des paiements</h1>
    <div class="subtitle">Client : <strong>{{ $client->nom }}</strong> — {{ $client->email }}</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Montant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ \Carbon\Carbon::parse($payment->date_payment)->format('d/m/Y') }}</td>
                <td>{{ number_format($payment->montant, 2, ',', ' ') }} €</td>
                <td class="badge-{{ str_replace('_', '-', $payment->status_payment) === 'payé' ? 'paye' : (str_replace('_', '-', $payment->status_payment) === 'en-attente' ? 'attente' : 'retard') }}">
                    {{ ucfirst(str_replace('_', ' ', $payment->status_payment)) }}
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#9ca3af;">Aucun paiement enregistré.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Généré le {{ now()->format('d/m/Y à H:i') }} — GestionClients</div>
</body>
</html>
