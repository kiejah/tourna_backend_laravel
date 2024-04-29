<?php

namespace App\Models;

use App\Models\Player;
use App\Models\GamesTable;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GamesTable extends Model
{
    use HasFactory;
    protected $fillable = ['player_id', 'win','loss', 'draw','points','gd','tournament_id'];
    public function player(){
        return $this->belongsTo(Player::class,'player_id');
    }
    public function player_one_games(){
        return $this->hasMany(Game::class,'player_one_id','player_id');
    }
    public function player_two_games(){
        return $this->hasMany(Game::class,'player_two_id','player_id');
    }
    
   
}
