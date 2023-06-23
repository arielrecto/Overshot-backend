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
}
