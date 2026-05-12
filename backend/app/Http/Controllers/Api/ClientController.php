<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreClientRequest;
use App\Http\Requests\Api\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * GET /api/clients?search=&status=
     */
    public function index(Request $request): JsonResponse
    {
        $query = Client::query()->with(['user', 'payments', 'license']);

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('statut_paiement', $request->status);
        }

        $clients = $query->orderBy('nom')->paginate(15);

        return response()->json(['data' => $clients]);
    }

    /**
     * POST /api/clients
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());

        return response()->json([
            'data'    => $client,
            'message' => 'Client créé avec succès.',
        ], 201);
    }

    /**
     * GET /api/clients/{id}
     */
    public function show(Client $client): JsonResponse
    {
        $client->load(['payments' => fn ($q) => $q->orderBy('date_payment', 'desc'), 'license' => fn ($q) => $q->orderBy('date_assignation', 'desc'), 'user']);

        return response()->json(['data' => $client]);
    }

    /**
     * PUT /api/clients/{id}
     */
    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());

        return response()->json([
            'data'    => $client->fresh(),
            'message' => 'Client mis à jour avec succès.',
        ]);
    }

    /**
     * DELETE /api/clients/{id}
     */
    public function destroy(Client $client): JsonResponse
    {
        // Revoke user access if linked
        if ($client->user_id && $client->user) {
            $client->user->delete();
        }

        $client->delete();

        return response()->json(['message' => 'Client supprimé avec succès.']);
    }

    /**
     * PUT /api/clients/{id}/relance
     */
    public function relance(Request $request, Client $client): JsonResponse
    {
        $validated = $request->validate([
            'relance_flag' => 'required|boolean',
            'date_relance' => 'nullable|date',
            'note_relance' => 'nullable|string|max:1000',
        ]);

        $client->update($validated);

        return response()->json([
            'data'    => $client->fresh(),
            'message' => 'Relance mise à jour.',
        ]);
    }
}
