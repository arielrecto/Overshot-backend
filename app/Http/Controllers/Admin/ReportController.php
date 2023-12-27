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
        )
    {

    }

    public function transaction(){

        $transactions = $this->transaction->get();


        $data = [
            'monthlyTransaction' => $this->monthlyTransaction(now()->year),
            'transactions' => $transactions
        ];


        return response($data, 200);

    }
    public function sales(){

        $orders = $this->order->withCount(['products'])->get();

        $data = [
            'monthlySales' => $this->monthlySales(now()->year),
            'orders' => $orders
        ];

        return response($data, 200);

    }

    private function monthlyTransaction($year){

        $monthlyTotal = [];


        for ($month = 1; $month <= 12; $month++){

            $totalTransaction = Transaction::whereMonth('created_at', $month)
                                ->whereYear('created_at', $year)
                                ->count();

            $fullBNameOfMonth = date('F', strtotime("{$year}-{$month}-1"));

            $monthlyTotal[$fullBNameOfMonth] = $totalTransaction;
        }


        return $monthlyTotal;
    }
    private function monthlySales($year){

        $monthlyTotal = [];


        for ($month = 1; $month <= 12; $month++){

            $totalTransaction = Order::whereMonth('created_at', $month)
                                ->whereYear('created_at', $year)
                                ->sum('total');

            $fullBNameOfMonth = date('F', strtotime("{$year}-{$month}-1"));

            $monthlyTotal[$fullBNameOfMonth] = $totalTransaction;
        }


        return $monthlyTotal;
    }
}
