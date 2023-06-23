<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Category\StoreCategoryAction;
use App\Actions\Category\StoreLevelsAction;
use App\Actions\Category\StoreSizesCategoryAction;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, StoreCategoryAction $storeCategoryAction)
    {
        $storeCategoryAction->handle($request);


        $categories = Category::with('sizes', 'levels')->get();


        return response(['categories' => $categories], 200);
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

    public function sizes(Request $request, StoreSizesCategoryAction $storeSizesCategoryAction){

        $storeSizesCategoryAction->handle($request);



        $categories = Category::with('sizes', 'levels')->get();

        return response([
            'categories' => $categories
        ], 200);
    }
    public function levels(Request $request, StoreLevelsAction $storeLevelsAction) {

        $storeLevelsAction->handle($request);

        $categories = Category::with('sizes', 'levels')->get();

        return response([
            'categories' => $categories
        ]);
    }
}
