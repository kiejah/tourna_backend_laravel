<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameRequest extends FormRequest
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
                'player_one_id'=>['required'],
                'player_one_score'=>['required'],
                'player_two_id'=>['required'],
                'player_two_score'=>['required'],
                'tournament_id'=>['required'],
                ];
        
    }
}
