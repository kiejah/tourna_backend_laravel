<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            'player_one'=> $this->playerOne,
            'player_two'=> $this->playerTwo,
            'player_one_score'=> $this->player_one_score,
            'player_two_score'=> $this->player_two_score,
            'tournament'=> $this->tournament,
            'url'=>asset('storage/'),
            //'player_url'=>route('players.show',$this->player_alias)
        ];
    }
}
