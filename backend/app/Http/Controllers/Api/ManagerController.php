<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreManagerRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    /**
     * GET /api/managers  (admin only)
     */
    public function index(): JsonResponse
    {
        $managers = User::role('manager')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'created_at']);

        return response()->json(['data' => $managers]);
    }

    /**
     * POST /api/managers  (admin only)
     */
    public function store(StoreManagerRequest $request): JsonResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('manager');

        return response()->json([
            'data'    => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'message' => 'Manager ajouté avec succès.',
        ], 201);
    }

    /**
     * DELETE /api/managers/{user}  (admin only)
     */
    public function destroy(User $user): JsonResponse
    {
        abort_if($user->hasRole('admin'), 403, 'Impossible de supprimer un administrateur.');

        $user->delete();

        return response()->json(['message' => 'Manager supprimé.']);
    }
}
