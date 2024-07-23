<?php

namespace App\Http\Controllers;

use App\Models\Financial;

class OrderController extends Controller
{

    public function create(Financial $financial)
    {
        $states = config('states');
        $financial->serviceType;
        $financial->registration;

        return view('payment.create-order', compact('financial', 'states'));
    }
}
