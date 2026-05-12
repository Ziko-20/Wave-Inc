<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePaymentRequest;
use App\Http\Requests\Api\UpdatePaymentRequest;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    /**
     * GET /api/clients/{client}/payments
     */
    public function index(Client $client): JsonResponse
    {
        $payments = $client->payments()->orderBy('date_payment', 'desc')->get();

        return response()->json(['data' => $payments]);
    }

    /**
     * POST /api/clients/{client}/payments
     */
    public function store(StorePaymentRequest $request, Client $client): JsonResponse
    {
        $payment = $client->payments()->create($request->validated());

        return response()->json([
            'data'    => $payment,
            'message' => 'Paiement ajouté avec succès.',
        ], 201);
    }

    /**
     * PUT /api/payments/{payment}
     */
    public function update(UpdatePaymentRequest $request, Payment $payment): JsonResponse
    {
        $payment->update($request->validated());

        return response()->json([
            'data'    => $payment->fresh(),
            'message' => 'Paiement mis à jour.',
        ]);
    }

    /**
     * DELETE /api/payments/{payment}
     */
    public function destroy(Payment $payment): JsonResponse
    {
        $payment->delete();

        return response()->json(['message' => 'Paiement supprimé.']);
    }
}
