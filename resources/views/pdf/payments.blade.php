<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1f2937; }

        .header { background-color: #4f46e5; color: white; padding: 20px; border-radius: 8px; margin-bottom: 24px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 4px 0 0; font-size: 12px; opacity: 0.85; }

        .client-info { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px 18px; margin-bottom: 24px; }
        .client-info p { margin: 4px 0; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead tr { background-color: #4f46e5; color: white; }
        th { padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 9px 12px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) td { background-color: #f9fafb; }

        .badge { display: inline-block; padding: 2px 10px; border-radius: 99px; font-size: 10px; font-weight: bold; }
        .badge-paye    { background: #d1fae5; color: #065f46; }
        .badge-attente { background: #fef3c7; color: #92400e; }
        .badge-retard  { background: #fee2e2; color: #991b1b; }

        .total { text-align: right; font-size: 14px; font-weight: bold; color: #4f46e5; }
        .footer { margin-top: 30px; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>Rapport des Paiements</h1>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    {{-- Infos client --}}
    <div class="client-info">
        <p><strong>Client :</strong> {{ $client->nom }}</p>
        <p><strong>Email :</strong> {{ $client->email }}</p>
        <p><strong>Téléphone :</strong> {{ $client->telephone }}</p>
    </div>

    {{-- Tableau paiements --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $index => $payment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ number_format($payment->montant, 2) }} MAD</td>
                <td>{{ \Carbon\Carbon::parse($payment->date_payment)->format('d/m/Y') }}</td>
                <td>
                    @if($payment->status_payment == 'payé')
                        <span class="badge badge-paye">Payé</span>
                    @elseif($payment->status_payment == 'en_attente')
                        <span class="badge badge-attente">En attente</span>
                    @elseif($payment->status_payment == 'en_retard')
                        <span class="badge badge-retard">En retard</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Total --}}
    <p class="total">
        Total : {{ number_format($payments->sum('montant'), 2) }} MAD
    </p>

    <div class="footer">Document généré automatiquement</div>

</body>
</html>