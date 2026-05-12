<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche client — {{ $client->nom }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a2e; }
        h1 { color: #22419A; font-size: 20px; }
        h2 { color: #439670; font-size: 15px; margin-top: 24px; border-bottom: 2px solid #439670; padding-bottom: 4px; }
        .info-grid { display: flex; gap: 20px; flex-wrap: wrap; margin: 12px 0; }
        .info-item { min-width: 180px; }
        .label { font-size: 10px; color: #6b7280; text-transform: uppercase; }
        .value { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #22419A; color: white; padding: 7px 10px; text-align: left; font-size: 11px; }
        td { padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .footer { margin-top: 30px; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <h1>Fiche client</h1>

    <div class="info-grid">
        <div class="info-item"><div class="label">Nom</div><div class="value">{{ $client->nom }}</div></div>
        <div class="info-item"><div class="label">Email</div><div class="value">{{ $client->email }}</div></div>
        <div class="info-item"><div class="label">Téléphone</div><div class="value">{{ $client->telephone }}</div></div>
        <div class="info-item"><div class="label">Statut</div><div class="value">{{ ucfirst(str_replace('_', ' ', $client->statut_paiement)) }}</div></div>
        <div class="info-item"><div class="label">Date maintenance</div><div class="value">{{ \Carbon\Carbon::parse($client->date_maintenance)->format('d/m/Y') }}</div></div>
        <div class="info-item"><div class="label">Nb licences</div><div class="value">{{ $client->licences_count }}</div></div>
    </div>

    <h2>Paiements</h2>
    <table>
        <thead><tr><th>#</th><th>Date</th><th>Montant</th><th>Statut</th></tr></thead>
        <tbody>
            @forelse($payments as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ \Carbon\Carbon::parse($p->date_payment)->format('d/m/Y') }}</td>
                <td>{{ number_format($p->montant, 2, ',', ' ') }} €</td>
                <td>{{ ucfirst(str_replace('_', ' ', $p->status_payment)) }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#9ca3af;">Aucun paiement.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Licences</h2>
    <table>
        <thead><tr><th>#</th><th>Nom</th><th>Quantité</th><th>Date assignation</th></tr></thead>
        <tbody>
            @forelse($licenses as $l)
            <tr>
                <td>{{ $l->id }}</td>
                <td>{{ $l->nom }}</td>
                <td>{{ $l->quantite_disponible }}</td>
                <td>{{ \Carbon\Carbon::parse($l->date_assignation)->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#9ca3af;">Aucune licence.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Généré le {{ now()->format('d/m/Y à H:i') }} — GestionClients</div>
</body>
</html>
