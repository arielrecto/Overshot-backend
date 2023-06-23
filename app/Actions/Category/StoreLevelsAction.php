<?php



namespace App\Actions\Category;

use App\Models\Category;
use App\Models\Level;
use Illuminate\Http\Request;

class StoreLevelsAction {

    public function handle (Request $request){

        $category = Category::where('name', $request->category)->first();

        $level = Level::create([
            'name' => $request->levels
        ]);

        $category->levels()->attach($level->id);
        return $level;
    }
}
