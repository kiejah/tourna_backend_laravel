<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameTableStandingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'player'=> $this->player,
            'as_p1'=>$this->player_one_games,
            'as_p2'=>$this->player_two_games,
            'win'=> $this->win,
            'loss'=> $this->loss,
            'draw'=> $this->draw,
            'points'=> $this->points,
            'gd'=> $this->gd,
            'games_played'=>($this->win+$this->loss+$this->draw)
        ];
    }
}