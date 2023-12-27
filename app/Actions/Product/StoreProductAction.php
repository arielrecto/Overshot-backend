<?php


namespace App\Actions\Product;

use App\Actions\ImageUploader;
use App\Models\Category;
use App\Models\Level;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Size;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreProductAction
{
    public function handle(Request $request)
    {
        $product = Product::create([
            'name' => $request->data['name'],
            'price' => $request->data['price'],
            'description' => $request->data['description']
        ]);

        $category = Category::where('name', $request->data['category'])->first();

        $product->categories()->attach($category->id);


        foreach($request->data['sizes'] as $_size){

            $size = Size::where('name', $_size['name'])->first();

            $product->sizes()->attach($size->id, ['price' => $_size['price']]);

        }
        foreach($request->data['levels'] as $_level){
            $level = Level::where('name', $_level['name'])->first();
            $product->levels()->attach($level->id, ['percent' => $_level['percent']]);
        }

        $image = $request->image;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName =  'img' . now() .'.'.'png';
        $filename = preg_replace('~[\\\\\s+/:*?"<>|+-]~', '-', $imageName);

        $imageDecoded = base64_decode($image);

        ProductImage::create([
            'name' => $imageName,
            'image_url' => asset('storage/product/image/' . $filename),
            'product_id' => $product->id
        ]);
        Storage::disk('public')->put('product/image/' . $filename, $imageDecoded);

        return $product;
    }
}
