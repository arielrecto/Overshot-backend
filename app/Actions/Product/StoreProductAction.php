<?php


namespace App\Actions\Product;

use App\Actions\ImageUploader;
use App\Models\Product;
use App\Models\ProductImage;
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

        $image = $request->image;

        $image = $request->image;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName =  'Img' . now() .'.'.'png';
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
