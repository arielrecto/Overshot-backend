<?php

namespace App\Http\Services;

use App\Actions\ImageUploader;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ProductService
{
    protected $image;
    protected $name;
    protected $description;
    protected $price;


    public function __construct(Request $request)
    {
        $this->image = $request->hasFile('image') ? $request->file('image') : null;
        $this->name = $request->data->name;
        $this->description = $request->data->description;
        $this->price = $request->data->price;
    }
    private function image(Product $product) : void
    {
        $uploader = new ImageUploader();
        $name = 'Img' . now() . '.' . $this->image->getClientOriginalExtension();
        $filename = preg_replace('~[\\\\\s+/:*?"<>|+-]~', '-', $name);
        $destination = 'public/product/image';
        $uploader->upload($this->image, $destination, $filename);
         ProductImage::create([
            'name' => $filename,
            'image_url' => asset('storage/product/image/' . $filename),
            'product_id' => $product->id
        ]);   
    }
    public function storeProduct()
    {
        $product = Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price
        ]);

        $this->image($product);

        if (!$product) {
            return abort(400, ['message' => 'Something Wrong']);
        }
        return response(['message' => 'Product Added Success'], 200);
    }
}
