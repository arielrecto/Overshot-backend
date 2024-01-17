<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DailySupply;
use App\Models\Supply;
use Illuminate\Http\Request;

class DailySupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $daily_supply = DailySupply::whereDay('created_at', now()->day)->first();

        return response(['daily_supply' => $daily_supply], 200);
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

        $daily_supply = $request->daily_supply;


        collect($daily_supply)->map(function ($supply) {
            $invent_supply = Supply::find($supply['id']);
            $invent_supply->update([
                'quantity' => $invent_supply->quantity - $supply['quantity']
            ]);
        });

        $supply = DailySupply::create([
            'supplies' => json_encode($daily_supply)
        ]);

        $supplies = Supply::get();

        return response([
            'supplies' => $supplies,
            'daily_supply' => $supply
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
