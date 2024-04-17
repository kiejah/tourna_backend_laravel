<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'player_name'=>['required','min:5','max:40'],
            //'player_alias'=>['required','unique:players,player_alias,'.$this->player->id],
            'player_alias'=>['required',Rule::unique('players')->ignore($this->player)],

        ];
    }
}
