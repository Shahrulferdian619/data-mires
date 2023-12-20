<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('purchasing.purchase_order.index');
    }
}
