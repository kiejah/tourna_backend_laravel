<?php

namespace App\Http\Controllers\Api;

use App\Models\GamesTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameTableStandingsCollection;

class GamesTableStandingController extends Controller
{
    //
    public function index()
    {
        //
        return new GameTableStandingsCollection(GamesTable::all()->sortByDesc('points'));
    }
    public function tournamentStandings($id){
        return new GameTableStandingsCollection(GamesTable::where('tournament_id',$id)->get()->sortByDesc('points')->sortByDesc('gd'));
    }

}
