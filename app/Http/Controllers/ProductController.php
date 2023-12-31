<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\Level;
use App\Models\Promo;
use App\Models\Supply;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Actions\Product\StoreProductAction;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct(public Product $product)
     {

     }


    public function index()
    {

        $year = now()->format('Y');

        $products = Product::with(['image', 'categories', 'sizes', 'levels', 'promo.promo' => function($q){
            $q->where('is_active', true);
        }])->withAvg('ratings', 'rate')->get();
        $supplies = Supply::where('category', 'Add On')->get();


        $productsByMonth =  $this->getProductByMonthAndHighestOrder($this->product);

        $bestSeller = Product::with(['image'])->withCount('orders')
        ->orderBy('orders_count', 'desc')
        ->first();


        $categories = Category::get();

        return response([
            'products' => $products,
            'categories' => $categories,
            'supplies' => $supplies,
            'productsByMonth'=> $productsByMonth,
            'bestSeller' => $bestSeller
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
        $product = Product::whereId($id)->with(['image'])->first();

        return response($product, 200);
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

    private function getProductByMonthAndHighestOrder(Product $product){

        $allMonths = range(1, 12);

        $data = [];



        foreach ($allMonths as $month) {
            $monthName = date("F", mktime(0, 0, 0, $month, 1)); // Get month name

            $productWithHighestOrderQuantity = Product::withCount(['orders' => function ($query) use ($month) {
                $query->select(DB::raw('coalesce(sum(op.quantity), 0) as total_quantity'))
                    ->join('order_product as op', 'orders.id', '=', 'op.order_id')
                    ->where('products.id', '=', DB::raw('op.product_id'))
                    ->whereMonth('orders.created_at', $month) // Filter by month
                    ->groupBy('op.product_id');
            }])
            ->orderByDesc('orders_count')
            ->first();

            $data[$monthName] = $productWithHighestOrderQuantity ?? 0;
        }

        return $data;

    }
}
