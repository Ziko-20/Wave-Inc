<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreLicenseRequest;
use App\Models\Client;
use App\Models\License;
use Illuminate\Http\JsonResponse;

class LicenseController extends Controller
{
    /**
     * GET /api/licenses
     */
    public function index(): JsonResponse
    {
        $licenses = License::with('client')->orderBy('date_assignation', 'desc')->get();

        return response()->json(['data' => $licenses]);
    }

    /**
     * POST /api/licenses  (standalone creation)
     */
    public function store(StoreLicenseRequest $request): JsonResponse
    {
        $license = License::create($request->validated());

        return response()->json([
            'data'    => $license->load('client'),
            'message' => 'Licence créée avec succès.',
        ], 201);
    }

    /**
     * DELETE /api/licenses/{license}
     */
    public function destroy(License $license): JsonResponse
    {
        $license->delete();

        return response()->json(['message' => 'Licence supprimée.']);
    }

    /**
     * POST /api/clients/{client}/licenses  (assign to client)
     */
    public function assign(StoreLicenseRequest $request, Client $client): JsonResponse
    {
        $license = $client->license()->create($request->validated());

        return response()->json([
            'data'    => $license,
            'message' => 'Licence assignée au client.',
        ], 201);
    }
}
