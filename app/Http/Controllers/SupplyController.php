<?php

namespace App\Http\Controllers;

use App\Actions\Supply\DeleteSupplyAction;
use App\Actions\Supply\StoreSupplyAction;
use App\Http\Requests\StoreSupplyRequest;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Supply::get();
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
    public function store(Request $request, StoreSupplyAction $action)
    {

        $action->handle($request);

        return response('item-added Successfully', 200);
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
    public function destroy($id, DeleteSupplyAction $deleteSupplyAction)
    {
        return $deleteSupplyAction->handle($id);
    }

    public function addStock(Request $request, string $id)
    {

        $request->validate([
            'quantity' => 'required',
            'manufacturer'=> 'required',
            'expiry_date' => 'required'
        ]);

        $supply = Supply::find($id);



        $supply->update([
            'quantity' => $supply->quantity + $request->quantity,
            'manufacturer' => $request->manufacturer,
            'expiry_date' => $request->expiry_date
        ]);


        $supplies = Supply::get();



        return response(['message' => 'stock quantity is Added', 'supplies' => $supplies]);
    }
}
