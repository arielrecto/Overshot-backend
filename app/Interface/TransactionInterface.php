<?php


namespace App\Interface;

use Illuminate\Http\Request;

interface TransactionInterface {


    public function store(Request $request);

}
