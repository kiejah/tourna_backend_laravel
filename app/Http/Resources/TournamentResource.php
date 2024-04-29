<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
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
            't_name'=> $this->t_name,
            't_prize'=> $this->t_prize,
            't_image_name'=>asset('storage/'. $this->t_image_name),
            'number_of_players'=> $this->number_of_players,
            'status'=> $this->status,
            'winner'=> $this->winner,
            'winner_points'=> $this->winner_points,
            //'player_url'=>route('players.show',$this->player_alias)
        ];
    }
}
