<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Dashboard\Admin\OverviewAction;
use App\Models\Supply;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function overview()
    {

        $totalUser = User::role('client')->get();
        $totalSupply = Supply::get();
        $totalTransactionOnline = Transaction::where('type', 'online')->get();
        $totalTransactionWalkin = Transaction::where('type', 'walk_in')->get();
        $transaction = Transaction::with('order.payment')->get();

        $data = [
            'monthlyTransaction' => $this->monthlyTransaction(now()->year),
            'total' => [
                'users' => $this->getTotalOfModel($totalUser),
                'supplies' => $this->getTotalOfModel($totalSupply),
                'online' => $this->getTotalOfModel($totalTransactionOnline),
                'walkin' => $this->getTotalOfModel($totalTransactionWalkin),
                'transactions' => $transaction
            ]
        ];
        return response([
            'overview' => $data
        ], 200);
    }
    private function getTotalOfModel($data){
        $count  = $data->count();


        return $count;
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
}
