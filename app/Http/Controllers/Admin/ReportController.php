<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __construct(
        public Transaction $transaction,
        public Order $order
    ) {
    }

    public function transaction()
    {

        $transactions = $this->transaction->get();


        $data = [
            'monthlyTransaction' => $this->monthlyTransaction(now()->year),
            'transactions' => $transactions
        ];


        return response($data, 200);
    }
    public function sales()
    {

        // $orders = $this->order->with('cart')->withCount('cart.cartProducts')->get();


        $orders = $this->order->with(['cart' => function ($q) {
            $q->withCount('cartProducts');
        }])
            ->whereHas('cart', function ($q) {
                $q->where('is_check_out', true);
            })
            ->get();

        $data = [
            'monthlySales' => $this->monthlySales(now()->year),
            'orders' => $orders
        ];

        return response($data, 200);
    }

    private function monthlyTransaction($year)
    {

        $monthlyTotal = [];


        for ($month = 1; $month <= 12; $month++) {

            $totalTransaction = Transaction::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

            $fullBNameOfMonth = date('F', strtotime("{$year}-{$month}-1"));

            $monthlyTotal[$fullBNameOfMonth] = $totalTransaction;
        }


        return $monthlyTotal;
    }
    private function monthlySales($year)
    {
        $monthlyTotal = [];

        for ($month = 1; $month <= 12; $month++) {
            $orders = Order::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->with(['cart' => function ($q) {
                    $q->where('is_check_out', true);
                }])
                ->get();

            $totalTransaction = $orders->sum(function ($order) {
                return optional($order->cart)->sum('total') ?? 0;
            });

            $fullBNameOfMonth = date('F', strtotime("{$year}-{$month}-1"));

            $monthlyTotal[$fullBNameOfMonth] = $totalTransaction;
        }

        return $monthlyTotal;
    }
    // private function monthlySales($year)
    // {

    //     $monthlyTotal = [];


    //     for ($month = 1; $month <= 12; $month++) {

    //         $totalTransaction = Order::whereMonth('created_at', $month)
    //             ->whereYear('created_at', $year)
    //             ->with(['cart' => function($q){
    //                 $q->where('is_check_out', true);
    //             }])  // Assuming there is a relationship between Order and Cart
    //             ->sum('cart.total');


    //         $fullBNameOfMonth = date('F', strtotime("{$year}-{$month}-1"));

    //         $monthlyTotal[$fullBNameOfMonth] = $totalTransaction;
    //     }


    //     return $monthlyTotal;
    // }
}
