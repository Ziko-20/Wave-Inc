<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreLicenseOfferRequest;
use App\Models\LicenseOffer;
use Illuminate\Http\JsonResponse;

class LicenseOfferController extends Controller
{
    /**
     * GET /api/license-offers
     */
    public function index(): JsonResponse
    {
        $offers = LicenseOffer::orderBy('prix')->get();

        return response()->json(['data' => $offers]);
    }

    /**
     * POST /api/license-offers  (admin only)
     */
    public function store(StoreLicenseOfferRequest $request): JsonResponse
    {
        $offer = LicenseOffer::create($request->validated());

        return response()->json([
            'data'    => $offer,
            'message' => 'Offre créée avec succès.',
        ], 201);
    }

    /**
     * DELETE /api/license-offers/{licenseOffer}  (admin only)
     */
    public function destroy(LicenseOffer $licenseOffer): JsonResponse
    {
        $licenseOffer->delete();

        return response()->json(['message' => 'Offre supprimée.']);
    }
}
