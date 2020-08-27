<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    //Transactions
    public function transactions(){
        return view('dashboard.balance.transaction');
    }
}
