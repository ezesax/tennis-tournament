<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Services\PlayerService;
use App\Services\SimulationService;
use App\Services\TournamentMatchService;
use App\Services\TournamentService;
use App\Validators\GenderValidator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class MainController extends Controller
{
    protected $simulationService;

    public function __construct(SimulationService $simulationService)
    {
        $this->simulationService = $simulationService;
    }

    public function simulate(Request $request)
    {
        try {
            $playersData = $request->get('players');

            if (!GenderValidator::validate($playersData)) {
                return response()->json(['message' => 'Todos los jugadores deben ser del mismo género'], 422);
            }

            $winner = null;
            $gender = $playersData[0]['gender'];
            $numberOfRounds = (int) log(count($playersData), 2);

            $tournament = $this->createTournament($gender);
            $players = $this->createPlayers($playersData, $tournament);

            //Comienza el torneo
            $tournament->status = "in_progress";
            $tournament = TournamentService::updateTournament($tournament->id, $tournament->toArray());

            for ($i = 0; $i < $numberOfRounds; $i++) {
                $matches = $this->createMatchesPerRound($players, $tournament, ($i+1));
                $winnersIds = $this->simulateMatches($matches, $tournament);
                $players = PlayerService::getPlayers($winnersIds);
    
                if (count($players) == 1) {
                    $winner = $players->first();

                    $tournament->status = "completed";
                    $tournament->winner_id = $winner->id;
                    $tournament = TournamentService::updateTournament($tournament->id, $tournament->toArray());
                }
            }

            return response()->json([
                'winner' => $winner], 200);

        } catch (ValidationException $ex) {
            return response()->json(['errors' => $ex->errors()], 422);
        } catch (QueryException $ex) {
            return response()->json(['message' => 'Database error'], 500);
        } catch (\Exception $ex) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    private function createTournament($gender): Tournament
    {
        return TournamentService::createTournament([
            "name" => "Tournament " . now()->format('Y-m-d H:i:s'),
            "gender" => $gender,
            "status" => "not_started"
        ]);
    }

    private function createPlayers($playersData, $tournament): Collection
    {
        $players = [];

            foreach ($playersData as $data) {
                $player = PlayerService::createPlayer($data);
    
                $tournament->players()->attach($player);
                $players[] = $player;
            }

            return new Collection($players);
    }

    private function createMatchesPerRound($players, $tournament, $round): array
    {
        $matches = [];

        for ($i = 0; $i < count($players); $i += 2) {
            $matches[] = TournamentMatchService::createTournamentMatch([
                'player_one_id' => $players[$i]->id,
                'player_two_id' => $players[$i + 1]->id,
                'round' => $round,
                'tournament_id' => $tournament->id
            ]);
        }

        return $matches;
    }

    private function simulateMatches($matches, $tournament): array
    {
        $winnersIds = [];

        foreach ($matches as $match) {
            $winner = $this->simulationService->simulateMatch($match, $tournament->gender);
            $match["winner_id"] = $winner;
            TournamentMatchService::updateTournamentMatch($match["id"], $match->toArray());

            $winnersIds[] = $winner;
        }

        return $winnersIds;
    }
}
