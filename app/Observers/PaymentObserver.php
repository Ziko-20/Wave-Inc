<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    public function saved(Payment $payment): void
    {
        $client = $payment->client;

        if (in_array($payment->status_payment, ['en_retard', 'en_attente'])) {
            $client->update([
                'relance_flag' => true,
                'date_relance' => now()->addDays(3),
            ]);
        }

        if ($payment->status_payment === 'payé') {
            $client->update([
                'relance_flag' => false,
                'date_relance' => null,
            ]);
        }
    }
}
