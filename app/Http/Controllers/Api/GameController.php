<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Models\GamesTable;
use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Http\Requests\GameRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameCollection;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new GameCollection(Game::all());  
    
    }
    public function tournamentGames($id){
        return new GameCollection(Game::where('tournament_id',$id)->get()); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GameRequest $request)
    {
        //check if tournament has second leg

    $tournament = Tournament::find($request->tournament_id);

    $p1_vs_p2 = Game::where('player_one_id',$request->player_one_id)->where('player_two_id',$request->player_two_id)->where('tournament_id',$request->tournament_id)->get();
    $p2_vs_p1 = Game::where('player_one_id',$request->player_two_id)->where('player_two_id',$request->player_one_id)->where('tournament_id',$request->tournament_id)->get();
    $games = Game::all();
    //return response()->json(['countp1vp2'=>count($p1_vs_p2),'countp2vp1'=>count($p2_vs_p1),'p2id'=>$request->player_two_id,'p1id'=>$request->player_one_id,'p1vp2'=>$p1_vs_p2,'p2vp1'=>$p2_vs_p1,]);

       if($tournament->has_second_leg=='1'){//has second leg true
            //check if both games have been played
           if( (count($p1_vs_p2)+count($p2_vs_p1)) == 2 || count($p1_vs_p2) == 2 || count($p2_vs_p1)== 2){// games already Played
                return response()->json(['data'=>"Both games Played"]);
            }else{//save the second game
                //return response()->json(['countp1vp2'=>count($p1_vs_p2),'countp2vp1'=>count($p2_vs_p1)]);
                $this->saveGame($request);
            }


        }elseif($tournament->has_second_leg=='2'){//has one leg
            // check if either of the game has been played
            if((count($p1_vs_p2)+count($p2_vs_p1)) == 1){//game already played
                return response()->json(['data'=>'Game already Played','tourna'=>$tournament]);
            }else{//save game
                $this->saveGame($request);
            }

        }else{//game has more than two legs
            return 0;
        }
        
    }
    public function saveGame($request){

        $game = Game::create($request->validated());

            //check if player game has been recorded
            $player1 = GamesTable::where('player_id',$request->player_one_id)->where('tournament_id',$request->tournament_id)->first();
            $player2 = GamesTable::where('player_id',$request->player_two_id)->where('tournament_id',$request->tournament_id)->first();
            if($player1 && $player2){
                //modify player1 and 2
                $player1->update(
                    [ 
                        'win'=>$this->win($request->player_one_score,$request->player_two_score) + $player1->win,
                        'loss'=>$this->loss($request->player_one_score,$request->player_two_score) + $player1->loss, 
                        'draw'=>$this->draw($request->player_one_score,$request->player_two_score) + $player1->draw,
                        'gd'=>$player1->gd+($request->player_one_score - $request->player_two_score),
                        'points'=>$player1->points + $this->winOrDrawPoints($request->player_one_score,$request->player_two_score),
                        'tournament_id'=>$request->tournament_id
                      ]
                );
                $player2->update(
                    [ 
                        'win'=>$this->win($request->player_two_score,$request->player_one_score) + $player2->win,
                        'loss'=>$this->loss($request->player_two_score,$request->player_one_score)+ $player2->loss, 
                        'draw'=>$this->draw($request->player_one_score,$request->player_two_score)+ $player2->draw,
                        'gd'=>$player2->gd+($request->player_two_score - $request->player_one_score),
                        'points'=>$player2->points + $this->winOrDrawPoints($request->player_two_score,$request->player_one_score),
                        'tournament_id'=>$request->tournament_id
                      ]
                );

            }elseif($player1 && !$player2){
                //mod player1
                $player1->update(
                    [ 
                        'win'=>$this->win($request->player_one_score,$request->player_two_score) + $player1->win,
                        'loss'=>$this->loss($request->player_one_score,$request->player_two_score) + $player1->loss, 
                        'draw'=>$this->draw($request->player_one_score,$request->player_two_score)+ $player1->draw,
                        'gd'=>$player1->gd+($request->player_one_score - $request->player_two_score),
                        'points'=>$player1->points + $this->winOrDrawPoints($request->player_one_score,$request->player_two_score),
                        'tournament_id'=>$request->tournament_id
                      ]
                );
                if(!$player2){
                    GamesTable::create([
                        'player_id'=>$request->player_two_id, 
                        'win'=>$this->win($request->player_two_score,$request->player_one_score),
                        'loss'=>$this->loss($request->player_two_score,$request->player_one_score), 
                        'draw'=>$this->draw($request->player_two_score,$request->player_one_score),
                        'gd'=>($request->player_two_score - $request->player_one_score),
                        'points'=>$this->winOrDrawPoints($request->player_two_score,$request->player_one_score),
                        'tournament_id'=>$request->tournament_id
                      ]);   
                }

            }elseif($player2 && !$player1){
                //mod player2
                $player2->update(
                    [ 
                        'win'=>$this->win($request->player_two_score,$request->player_one_score)+ $player2->win,
                        'loss'=>$this->loss($request->player_two_score,$request->player_one_score)+ $player2->loss, 
                        'draw'=>$this->draw($request->player_one_score,$request->player_two_score)+ $player2->draw,
                        'gd'=>$player2->gd + ($request->player_two_score - $request->player_one_score),
                        'points'=>$player2->points + $this->winOrDrawPoints($request->player_two_score,$request->player_one_score),
                        'tournament_id'=>$request->tournament_id
                      ]
                );
                if(!$player1){
                    GamesTable::create([
                        'player_id'=>$request->player_one_id, 
                        'win'=>$this->win($request->player_one_score,$request->player_two_score),
                        'loss'=>$this->loss($request->player_one_score,$request->player_two_score), 
                        'draw'=>$this->draw($request->player_one_score,$request->player_two_score),
                        'gd'=>($request->player_one_score - $request->player_two_score),
                        'points'=>$this->winOrDrawPoints($request->player_one_score,$request->player_two_score),
                        'tournament_id'=>$request->tournament_id
                      ]);
                }

            }else{
                if(!$player1){
                    GamesTable::create([
                        'player_id'=>$request->player_one_id, 
                        'win'=>$this->win($game->player_one_score,$game->player_two_score),
                        'loss'=>$this->loss($game->player_one_score,$game->player_two_score), 
                        'draw'=>$this->draw($game->player_one_score,$game->player_two_score),
                        'gd'=>$this->goalDiff($game->player_one_score,$game->player_two_score),
                        'points'=>$this->winOrDrawPoints($game->player_one_score,$game->player_two_score),
                        'tournament_id'=>$request->tournament_id
                      ]);
                }
                if(!$player2){
                    GamesTable::create([
                        'player_id'=>$request->player_two_id, 
                        'win'=>$this->win($request->player_two_score,$request->player_one_score),
                        'loss'=>$this->loss($request->player_two_score,$request->player_one_score), 
                        'draw'=>$this->draw($request->player_two_score,$request->player_one_score),
                        'gd'=>$this->goalDiff($game->player_two_score,$game->player_one_score),
                        'points'=>$this->winOrDrawPoints($request->player_two_score,$request->player_one_score),
                        'tournament_id'=>$request->tournament_id
                      ]);  
                }
            }

            return response()->json([
                'data'=>[
                    'message'=>true,
                    'request'=>$request->validated(),
                    'standings'=> GamesTable::all()    
                ]]
            );


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function winOrDrawPoints($score1,$score2){
        if($score1 > $score2){
            return 3;
        }
        elseif($score1 < $score2){
            return 0;
        }else{
            return 1;
        }
    }
    public function win($score1,$score2){
        if($score1 > $score2){
            return 1;
        }
        elseif($score1 < $score2){
            return 0;
        }else{
            return 0;
        }
    }
    public function loss($score1,$score2){
        if($score1 > $score2){
            return 0;
        }
        elseif($score1 < $score2){
            return 1;
        }else{
            return 0;
        }
    }
    public function draw($score1,$score2){
        if($score1 > $score2){
            return 0;
        }
        elseif($score1 < $score2){
            return 0;
        }else{
            return 1;
        }
    }
    public function goalDiff($score1,$score2){
        return ($score1 - $score2);
    }


    public function show(Game $game)
    {
        //
        return new GameResource($game);
    }
    public function playerGames($player_id,$tournament_id)
    {
        $winner_games= Game::where('player_one_id',$player_id)->orWhere('player_two_id')->where('tournament_id',$tournament_id)->get();
        
        return response()->json($winner_games);

        //return new GameResource($game);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GameRequest $request, Game $game)
    {
        //get previous match scores

        //player 0ne scores and points 
        $player_one_id = $game->player_one_id;
        $player_one_score = $game->player_one_score;
        $player_two_id = $game->player_two_id;
        $player_two_score = $game->player_two_score;

//dd($request);
        //subtract player scores and points from previous games_stables
        if($player_one_score == $player_two_score){//was draw
            $player1 = GamesTable::where('player_id',$player_one_id)->where('tournament_id',$game->tournament_id)->first();
            $player1->update(
                [ 
                    'draw'=>$player1->draw - 1,
                    'gd'=>$player1->gd+0,
                    'points'=>$player1->points - 1,
                ]
            );
            $player2 = GamesTable::where('player_id',$player_two_id)->where('tournament_id',$game->tournament_id)->first();
            $player2->update(
                [ 
                    'draw'=>$player2->draw - 1,
                    'gd'=>$player2->gd+0,
                    'points'=>$player2->points - 1,
                ]
            );

        }elseif($player_one_score > $player_two_score){ //player one had won
            $player1 = GamesTable::where('player_id',$player_one_id)->where('tournament_id',$game->tournament_id)->first();
            $player1->update(
                [ 
                    'win'=>$player1->win - 1,
                    'gd'=>$player1->gd-($player_one_score - $player_two_score),
                    'points'=>$player1->points - 3,
                ]
            );
            $player2 = GamesTable::where('player_id',$player_two_id)->where('tournament_id',$game->tournament_id)->first();
            $player2->update(
                [ 
                    'loss'=>$player2->loss - 1,
                    'gd'=>$player2->gd - ($player_two_score - $player_one_score),
                ]
            );

        }else{// player two had won
            $player1 = GamesTable::where('player_id',$player_one_id)->where('tournament_id',$game->tournament_id)->first();
            $player1->update(
                [ 
                    'loss'=>$player1->loss - 1,
                    'gd'=>$player1->gd - ($player_one_score - $player_two_score),
                ]
            );
        
            
            $player2 = GamesTable::where('player_id',$player_two_id)->where('tournament_id',$game->tournament_id)->first();
            $player2->update(
                [ 
                    'win'=>$player2->win - 1,
                    'gd'=>$player2->gd -($player_two_score - $player_one_score),
                    'points'=>$player2->points - 3,
                ]
            );


        }

        //now update the games table with request values

        $game->update($request->validated());

        if($request->player_one_score==$request->player_two_score){//if draw
            $player1 = GamesTable::where('player_id',$request->player_one_id)->where('tournament_id',$request->tournament_id)->first();
            $player1->update(
                [ 
                    'draw'=>$player1->draw + 1,
                    'gd'=>$player1->gd+0,
                    'points'=>$player1->points+ 1,
                ]
            );
            $player2 = GamesTable::where('player_id',$request->player_two_id)->where('tournament_id',$request->tournament_id)->first();
            $player2->update(
                [ 
                    'draw'=>$player2->draw + 1,
                    'gd'=>$player2->gd+0,
                    'points'=>$player2->points + 1,
                ]
            );

        }elseif($request->player_one_score > $request->player_two_score){//player one win
            $player1 = GamesTable::where('player_id',$request->player_one_id)->where('tournament_id',$request->tournament_id)->first();
            $player1->update(
                [ 
                    'win'=>$player1->win + 1,
                    'gd'=>$player1->gd+($request->player_one_score-$request->player_two_score),
                    'points'=>$player1->points + 3,
                ]
            );
            $player2 = GamesTable::where('player_id',$request->player_two_id)->where('tournament_id',$request->tournament_id)->first();
            $player2->update(
                [ 
                    'loss'=>$player2->loss + 1,
                    'gd'=>$player2->gd+($request->player_two_score-$request->player_one_score),
                ]
            );

        }else{//player two win
            $player2 = GamesTable::where('player_id',$request->player_two_id)->where('tournament_id',$request->tournament_id)->first();
            $player2->update(
                [ 
                    'win'=>$player2->win + 1,
                    'gd'=>$player2->gd+($request->player_two_score-$request->player_one_score),
                    'points'=>$player2->points + 3,
                ]
            );
            $player1 = GamesTable::where('player_id',$request->player_one_id)->where('tournament_id',$request->tournament_id)->first();
            $player1->update(
                [ 
                    'loss'=>$player1->loss + 1,
                    'gd'=>$player1->gd+($request->player_one_score-$request->player_two_score),
                ]
            );

        }

        return response()->json("game updated updated!");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
