<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Http\Requests\TournamentRequest;
use App\Http\Resources\TournamentResource;
use App\Http\Resources\TournamentCollection;

class TournamentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return new TournamentCollection(Tournament::all());
        // $tournaments = Tournament::all();
        // return response()->json(["status" => "success", "count" => count($tournaments), "data" => $tournaments]);
   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $formFields= $request->validate([
            't_name'=>'required',
            't_image_name'=>['required'],
            'has_second_leg'=>['required'],
            't_prize'=>['required'],
            't_desc'=>['required']

       ]);
        
        if($formFields){
            if($request->hasFile('t_image_name')){
                $formFields['t_image_name']= $request->file('t_image_name')->store('tournament_posters','public');
               }
               Tournament::create($formFields);
               return response()->json([
                'message'=>"Image saved", 
                ]);
        }else{
            return response()->json([
                'message'=>"Form not Validated", 
                ]);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tournament $tournament)
    {
        //
        return new TournamentResource($tournament);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
