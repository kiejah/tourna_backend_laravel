<?php

namespace App\Models;

use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;
    protected $fillable = ['player_one_id', 'player_one_score','player_two_id', 'player_two_score','tournament_id'];

    public function playerOne(){
        return $this->belongsTo(Player::class,'player_one_id');
    }
    public function playerTwo(){
        return $this->belongsTo(Player::class,'player_two_id');
    }
    public function tournament(){
        return $this->belongsTo(Tournament::class,'tournament_id');
    }
    
   
}
