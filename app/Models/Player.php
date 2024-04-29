<?php

namespace App\Models;

use App\Models\Game;
use App\Models\GamesTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;
    protected $fillable = ['player_name', 'player_alias'];

    public function playerOneGames(){
        return $this->hasMany(Game::class,'player_one_id');
    }
    public function playerTwoGames(){
        return $this->hasMany(Game::class,'player_two_id');
    }
    public function standings(){
        return $this->hasMany(GamesTable::class,'player_id');
    }
    // public function as_playerOne(): HasManyThrough
    // {
    //     return $this->hasManyThrough(Player::class, Game::class, 'player_one_id',);
    // }
    
}
