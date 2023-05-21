<?php


namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Http\Request;

class StoreCategoryAction {

    public function handle(Request $request){

        $category = Category::create([
            'name' => $request['name']
        ]);


        return $category;

    }
}
