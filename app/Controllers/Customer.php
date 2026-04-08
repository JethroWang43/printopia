<?php

namespace App\Controllers;

class Customer extends BaseController
{
    public function index()
    {
        $data = [
            'title'       => 'Customer Dashboard | Printopia',
            'username'    => session()->get('user_name') ?? 'Valued Customer',
            'orderCount'  => 0, 
            'designCount' => 0,
            'notifCount'  => 0
        ];

        return view('customer_view', $data);
    }
}