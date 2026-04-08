<?php

namespace App\Controllers;

class Customer extends BaseController
{
    public function index()
    {
        // For now, using session or static data for the UI
        $data = [
            'title'       => 'Customer Dashboard | Printopia',
            'username'    => session()->get('user_name') ?? 'Valued Customer',
            'orderCount'  => 0, 
            'designCount' => 0,
            'notifCount'  => 0
        ];

        return view('customer_view', $data);
    }

    // Future methods for Brandon and Zai to fill with DB logic
    public function getOrders() { /* AJAX logic here */ }
}