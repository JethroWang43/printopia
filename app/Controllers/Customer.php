<?php

namespace App\Controllers;

class Customer extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // 1. Get the current user from the session
        $userName = session()->get('user_name') ?? 'Guest';

        // 2. Fetch the designs for this specific user
        $designs = $db->table('gallery_designs')
                    ->where('customer_name', $userName)
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->getResultArray();

        $data = [
            'title'       => 'Customer Dashboard | Printopia',
            'username'    => $userName,
            'orderCount'  => 0, 
            'designCount' => count($designs), // This updates the stat box automatically!
            'notifCount'  => 0,
            'designs'     => $designs // This sends the data to the view
        ];

        return view('customer_view', $data);
    }
}