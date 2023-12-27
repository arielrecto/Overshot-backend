<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class CMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function carousel()
    {

        $carousels = Carousel::where('is_archive', false)->get();


        return response($carousels, 200);
    }

    public function bestSeller()
    {


        $topProducts = Product::with(['image', 'ratings'])->withAvg('ratings', 'rate')->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(1)->get();



        return response([
            'topProducts' => $topProducts
        ], 200);
    }

    public function mostPopular()
    {

        $topProducts = Product::with(['ratings', 'image'])
            ->withAvg('ratings', 'rate')
            ->orderByDesc('ratings_rate_avg')
            ->take(1)->get();


        return response([
            'topProducts' => $topProducts
        ], 200);
    }

    public function addCarousel(Request $request)
    {

        $request->validate([
            'image_url' => 'required',
            'caption' => 'required',
        ]);


        Carousel::create([
            'image_url' => $request->image_url,
            'caption' => $request->caption
        ]);


        return response([
            'message' => 'Data Upload Success'
        ], 200);
    }
    public function archiveCarousel($id)
    {

        $carousel = Carousel::find($id);
        $carousel->update([
            'is_archive' => true,
        ]);

        $carousels = Carousel::latest()->where('is_archive', false)->get();

        return response([
            'message' => 'Header Archived Success',
            'carousels' => $carousels
        ], 200);
    }

    public function addImage(Request $request)
    {


        $request->validate([
            'image' => 'sometimes|base64mimes:jpeg,jpg',
        ]);


        $image = $request->image;  // your base64 encoded
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName =  'img' . now() . '.' . 'png';
        $filename = preg_replace('~[\\\\\s+/:*?"<>|+-]~', '-', $imageName);

        $imageDecoded = base64_decode($image);

        Storage::disk('public')->put('gallery/' . $filename, $imageDecoded);

        Gallery::create([
            'name' => $filename,
            'image_url' =>   asset('storage/gallery/' . $filename),
        ]);


        $gallery = Gallery::latest()->get();


        return response([
            'gallery' => $gallery
        ], 200);
    }
    public function gallery()
    {
        $gallery = Gallery::latest()->get();

        return response([
            'gallery' => $gallery
        ], 200);
    }
}
