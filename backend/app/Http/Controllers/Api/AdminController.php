<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with('children')->get(); // A felhasználók és gyermekeik lekérdezése
        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Felhasználók és gyermekeik listázása sikeresen megtörtént.'
        ]);
    }

    /**
     * Egy adott felhasználó törlése admin által.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Felhasználó nem található.'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Felhasználó sikeresen törölve.'
        ], 200);
    }

    /**
     * Egy felhasználó adminná tétele.
     */
    public function promoteToAdmin($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Felhasználó nem található.'
            ], 404);
        }

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Ez a felhasználó már admin.'
            ], 400);
        }

        $user->role = 'admin';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Felhasználó adminná léptetve.'
        ], 200);
    }

    /**
     * Összes gyermek listázása admin számára.
     */
    public function listChildren()
    {
        $children = Child::with('user')->get(); // Gyermekek és szüleik lekérdezése
        return response()->json([
            'success' => true,
            'data' => $children,
            'message' => 'Gyermekek listázása sikeresen megtörtént.'
        ]);
    }

    /**
     * Egy adott gyermek törlése admin által.
     */
    public function deleteChild($id)
    {
        $child = Child::find($id);

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'Gyermek nem található.'
            ], 404);
        }

        $child->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gyermek sikeresen törölve.'
        ], 200);
    }

}
