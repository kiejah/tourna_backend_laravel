<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id'=> $this->id,
            'player_name'=> $this->player_name,
            'player_alias'=> $this->player_alias,
            //'player_url'=>route('players.show',$this->player_alias)
            // 'games_as_player_one'=> $this->playerOneGames,
            // 'games_as_player_two'=> $this->playerTwoGames,
        ];
    }
}
