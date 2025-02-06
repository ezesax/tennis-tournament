<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Trae torneos completados según los filtros date y gender.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompletedTournaments(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'nullable|date',
                'gender' => 'nullable|in:male,female'
            ]);
    
            $query = Tournament::where('status', 'completed')
                ->with(['players', 'matches', 'winner']);
    
            if (!empty($validated['date'])) {
                $query->whereDate('created_at', $validated['date']);
            }
    
            if (!empty($validated['gender'])) {
                $query->where('gender', $validated['gender']);
            }
    
            $tournaments = $query->paginate(10);

            if (count($tournaments) === 0) {
                return response()->json(['message' => 'No se han encontrado torneos'], 404);
            }
    
            return response()->json($tournaments, 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
