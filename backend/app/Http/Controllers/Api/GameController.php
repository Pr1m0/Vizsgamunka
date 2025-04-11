<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function game_query()
    {
        $games = Game::all();

        return response()->json([
            'success' => true,
            'data' => $games,
            'message' => 'Játékok sikeresen lekérdezve.'
        ], 200);
    }

    public function add_game(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string'
        ]);

        $game = Game::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $game,
            'message' => 'Játék sikeresen hozzáadva.'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'age_group' => 'sometimes|required|integer'
        ]);

        $game = Game::find($id);

        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Játék nem található.'
            ], 404);
        }

        $game->update($request->only('title', 'description', 'age_group'));

        return response()->json([
            'success' => true,
            'data' => $game,
            
            'message' => 'Játék frissítve.'
        ], 200);
    }

    public function destroy($id)
    {
        $game = Game::find($id);

        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Játék nem található.'
            ], 404);
        }

        $game->delete();

        return response()->json([
            'success' => true,
            'message' => 'Játék törölve.'
        ], 200);
    }
    
}
