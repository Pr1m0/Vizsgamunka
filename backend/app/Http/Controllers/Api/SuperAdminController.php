<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function listAdmins()
    {
        $admins = User::where('role', 'admin')->orWhere('role', 'superadmin')->get();

        return response()->json([
            'success' => true,
            'data' => $admins,
            'message' => 'Admin felhasználók listázása sikeres.'
        ]);
    }

    /**
     * Egy admin superadminná tétele.
     */
    public function promoteToSuperAdmin($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Felhasználó nem található.'
            ], 404);
        }

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Csak adminok léptethetők elő superadminná.'
            ], 403);
        }

        $user->role = 'superadmin';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Felhasználó sikeresen superadmin lett.'
        ], 200);
    }
}
