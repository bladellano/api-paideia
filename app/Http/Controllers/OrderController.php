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

        if($financial->paid == 1)
            return view('payment.paid-order', compact('financial'));

        return view('payment.create-order', compact('financial', 'states'));
    }
}
