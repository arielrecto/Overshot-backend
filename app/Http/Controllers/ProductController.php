<?php

namespace App\Http\Controllers;

use App\Actions\Product\StoreProductAction;
use App\Http\Requests\StoreProductRequest;
use App\Http\Services\ProductService;
use App\Models\Category;
use App\Models\Level;
use App\Models\Product;
use App\Models\Size;
use App\Models\Supply;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $products = Product::with('image', 'categories', 'sizes', 'levels')->get();
        $supplies = Supply::where('category', 'Add On')->get();

        $categories = Category::get();

        return response([
            'products' => $products,
            'categories' => $categories,
            'supplies' => $supplies
        ], 200);
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
    public function store(Request $request, StoreProductAction $storeProductAction)
    {
        $product =  $storeProductAction->handle($request);

        if (!$product) {

            return abort(401);
        }

        return response([
            'product' => $product
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

    public function otherInfo()
    {


        $category = Category::with('sizes','levels')->get();


        return response([
            'category' => $category,
        ]);
    }
}
