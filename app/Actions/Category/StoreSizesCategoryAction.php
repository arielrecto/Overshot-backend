<?php



namespace App\Actions\Category;

use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;

class StoreSizesCategoryAction {


    public function handle (Request $request) {


        $category = Category::where('name', $request->category)->first();


        foreach($request->sizes as $_size) {

            $size = Size::create(['name' => $_size['name']]);


            $category->sizes()->attach($size->id);

        }


        return $category;
    }
}
