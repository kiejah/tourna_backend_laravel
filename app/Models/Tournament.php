<?php

namespace App\Models;

use App\Models\Game;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tournament extends Model
{
    use HasFactory;
    protected $fillable = ['t_name','t_prize','user_id','t_image_name','number_of_players','status','winner','winner_points','has_second_leg','t_desc'];
    
    public function games(){
        return $this->hasMany(Game::class,'tournament_id');
    }


}
