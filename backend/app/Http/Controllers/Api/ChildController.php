<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Child;  

class ChildController extends Controller
{
    
   
    public function child_query(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $children = Child::with('user:id,name')->get();
        } else {
            $children = $user->children()->with('user:id,name', 'games')->get();
        }

        return response()->json([
            'success' => true,
            'data' => $children,
            'message' => 'Gyermekek sikeresen lekérdezve.'
        ], 200);
    }

    
    public function add_child(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:3|max:5'
        ]);

        $user = auth()->user();

        $child = Child::create([
            'name' => $request->name,
            'age' => $request->age,
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'data' => $child,
            'message' => 'Gyermek sikeresen létrehozva!'
        ], 201);
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string',
            'age' => 'sometimes|required|integer|min:3|max:9'
        ]);

        $user = auth()->user();
        $child = Child::findOrFail($id);

        if ($user->id !== $child->user_id && $user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Hozzáférés megtagadva'], 403);
        }

        $child->update($request->only('name', 'age'));

        return response()->json([
            'success' => true,
            'data' => $child,
            'message' => 'Gyermek frissítve.'
        ], 200);
    }

    
    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        $child = Child::findOrFail($id);

        // Csak a saját gyerekét törölheti a szülő, admin mindenkit
        if ($user->id !== $child->user_id && $user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Hozzáférés megtagadva'], 403);
        }

        $child->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gyermek törölve.'
        ], 200);
    }
    public function assignGame(Request $request, $childId)
    {
    $request->validate([
        'game_id' => 'required|exists:games,id'
    ]);

    $child = Child::findOrFail($childId);
    $child->games()->attach($request->game_id);

    return response()->json([
        'success' => true,
        'message' => 'Játék sikeresen hozzárendelve a gyermekhez.'
    ], 200);
    }
    public function getGamesForChild($id)
    {
    $child = Child::with('games')->findOrFail($id);

    return response()->json([
        'success' => true,
        'data' => $child->games
    ]);
    }
    public function removeGame($childId, $gameId)
    {
    $child = auth()->user()->children()->findOrFail($childId);
    $child->games()->detach($gameId);

    return response()->json(['success' => true, 'message' => 'Játék eltávolítva.']);
    }
}
