<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Carbon;
use App\Notifications\OrderStatus;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __construct(
        public Transaction $transaction,
        public Order $order
    ) {
    }

    public function transaction(Request $request)
    {
        $year = now()->year;

        $filter = $request->filter;



        $transactions = $this->transaction->get();

        $report = $this->monthlyTransactions($year);
        $tagline = 'Monthly Transaction';


        $total = Transaction::count();

        if($filter !== null){
            $report = $this->weeklyTransactions($year);
            $tagline = 'Weekly Transaction';
        }

        $data = [
            'report' => $report,
            'transactions' => $transactions,
            'date_issued' => now()->format('F d, Y'),
            'tagline' => $tagline,
            'total' => $total
        ];




        return response($data, 200);
    }
    public function order(Request $request){
        $year = now()->year;

        $filter = $request->filter;

        $report = $this->monthlyTransactions($year);
        $tagline = 'Monthly Orders';


        $total = Order::where('status', OrderStatusEnum::DONE->value)->count();


        $report = $this->monthlyOrders($year);


        if($filter !== null){
            $report = $this->weeklyOrders($year);
            $tagline = 'Weekly Transaction';
        }


        $data = [
            'report' => $report,
            'date_issued' => now()->format('F d, Y'),
            'tagline' => $tagline,
            'total' => $total
        ];




        return response($data, 200);



    }
    public function sales(Request $request)
    {
        $filter = $request->filter;
        // $orders = $this->order->with('cart')->withCount('cart.cartProducts')->get();
        $year = now()->year;

        $orders = $this->order->with(['cart' => function ($q) {
            $q->withCount('cartProducts');
        }])
            ->whereHas('cart', function ($q) {
                $q->where('is_check_out', true);
            })->where('status', OrderStatusEnum::DONE->value)
            ->get();


        $tagline = 'Monthly Sales';

        $total = Cart::whereHas('order', function ($query) {
            $query->where('status', OrderStatusEnum::DONE->value);
        })
            ->where('is_check_out', true)
            ->sum('total');


        $report =  $this->monthlySales($year);

        if($filter !== null){
            $report = $this->weeklyReports($year);
            $tagline = 'Weekly Sales';
        }



        $data = [
            'reportData' => $report,
            'orders' => $orders,
            'tagline' => $tagline,
            'date_issued' => now()->format('F d, Y'),
            'total' => $total
        ];




        return response($data, 200);
    }

    private function monthlyTransactions($year)
    {
        $monthlyTransactions = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $totalTransactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();

            $fullMonthName = $startDate->format('F');

            $monthlyTransactions[] = [
                'name' => strtolower($fullMonthName), // Convert month name to lowercase if needed
                'total' => $totalTransactions,
            ];
        }

        return $monthlyTransactions;
    }
    private function weeklyTransactions($year)
{
    $weeklyTransactions = [];

    for ($month = 1; $month <= 12; $month++) {
        $firstDayOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $lastDayOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

        // Split the month into weeks
        $weeks = [];
        $currentWeekStart = $firstDayOfMonth->copy()->startOfWeek();
        $currentWeekEnd = $currentWeekStart->copy()->endOfWeek();

        while ($currentWeekEnd->lt($lastDayOfMonth) || $currentWeekEnd->eq($lastDayOfMonth)) {
            $weeks[] = [
                'name' => 'Week of ' . $currentWeekStart->format('F j') . ' - ' . $currentWeekEnd->format('F j'),
                'total' => Transaction::whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])->count(),
            ];

            $currentWeekStart->addWeek();
            $currentWeekEnd->addWeek();
        }

        $weeklyTransactions = array_merge($weeklyTransactions, $weeks);
    }

    return $weeklyTransactions;
}
    private function monthlySales($year)
    {
        $monthlyTotal = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = now()->setDate($year, $month, 1)->startOfMonth();
            $endDate = now()->setDate($year, $month, 1)->endOfMonth();

            $orders = Order::whereBetween('created_at', [$startDate, $endDate])
                ->with(['cart' => function ($q) {
                    $q->where('is_check_out', true);
                }])->where('status', OrderStatusEnum::DONE->value)
                ->get();

            $totalTransaction = $orders->sum(function ($order) {
                return optional($order->cart)->sum('total') ?? 0;
            });

            $fullMonthName = date('F', strtotime("{$year}-{$month}-1"));

            $monthlyTotal[] = [
                'name' => "Month of {$fullMonthName} ({$startDate->format('Y-m-d')} - {$endDate->format('Y-m-d')})",
                'totalTransactions' => $totalTransaction,
            ];
        }

        return $monthlyTotal;
    }
    private function weeklyReports($year)
    {
        $weeklyReports = [];

        // Assuming 52 weeks in a year for simplicity
        for ($week = 1; $week <= 52; $week++) {
            $startDate = now()->setISODate($year, $week)->startOfWeek();
            $endDate = now()->setISODate($year, $week)->endOfWeek();

            $orders = Order::whereBetween('created_at', [$startDate, $endDate])
                ->with(['cart' => function ($q) {
                    $q->where('is_check_out', true);
                }])->where('status', OrderStatusEnum::DONE->value)
                ->get();

            $weeklyReport = [
                'name' => "Week {$week} ({$startDate->format('Y-m-d')} - {$endDate->format('Y-m-d')})",
                'totalTransactions' => 0,
            ];

            foreach ($orders as $order) {
                $totalTransaction = optional($order->cart)->sum('total') ?? 0;
                $weeklyReport['totalTransactions'] += $totalTransaction;
            }

            $weeklyReports[] = $weeklyReport;
        }

        return $weeklyReports;
    }
    private function monthlyOrders($year)
{
    $monthlyOrders = [];

    for ($month = 1; $month <= 12; $month++) {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderStatusEnum::DONE->value)
            ->get();

        $totalOrders = $orders->count();

        $fullMonthName = $startDate->format('F');

        $monthlyOrders[] = [
            'name' => strtolower($fullMonthName), // Convert month name to lowercase if needed
            'total' => $totalOrders,
        ];
    }

    return $monthlyOrders;
}
private function weeklyOrders($year)
{
    $weeklyOrders = [];

    for ($month = 1; $month <= 12; $month++) {
        $firstDayOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $lastDayOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

        // Split the month into weeks
        $weeks = [];
        $currentWeekStart = $firstDayOfMonth->copy()->startOfWeek();
        $currentWeekEnd = $currentWeekStart->copy()->endOfWeek();

        while ($currentWeekEnd->lt($lastDayOfMonth) || $currentWeekEnd->eq($lastDayOfMonth)) {
            $orders = Order::whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])
                ->where('status', OrderStatusEnum::DONE->value)
                ->get();

            $totalOrders = $orders->count();

            $weeks[] = [
                'name' => 'Week of ' . $currentWeekStart->format('F j') . ' - ' . $currentWeekEnd->format('F j'),
                'total' => $totalOrders,
            ];

            $currentWeekStart->addWeek();
            $currentWeekEnd->addWeek();
        }

        $weeklyOrders = array_merge($weeklyOrders, $weeks);
    }

    return $weeklyOrders;
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
