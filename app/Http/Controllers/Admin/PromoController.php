<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promo;
use App\Models\Product;
use App\Models\PromoProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promos = Promo::all();


        return response(['promos' => $promos], 200);
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
        $request->validate([
            'name' => 'required',
            'percent' => 'required',
            'decimal_value' => 'required'
        ]);


        $promo_code =  'PRM-'. uniqid();

        Promo::create([
            'code' => $promo_code,
            'name' => $request->name,
            'percent' => $request->percent,
            'decimal_value' => $request->decimal_value
        ]);



        return response(['message' => 'Promo Added'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id, Request $request)
    {
        $promo = Promo::with('products')->whereId($id)->first();

        $products = Product::with(['image'])->get();

        /*->whereHas('promo.promo', function ($q){
            $q->where('is_active', false);
        })->*/


        $query = $request->query('products');

        if($query !== null) {

            $query = json_decode($query);

            foreach($query as $product){

              PromoProduct::create([
                'product_id' => $product->id,
                'promo_id' => $promo->id
              ]);


              return response(['promo' => $promo,
              'message' => 'Promo Added to Product Success'], 200);
            }
        }




        return response([
            'promo' => $promo,
            'products' => $products
        ], 200);
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
