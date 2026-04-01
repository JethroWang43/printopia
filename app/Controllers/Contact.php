<?php
namespace App\Controllers;

class Contact extends BaseController {
    public function index() {
        $data = array(
            'title' => 'Printopia - Contact Us'
        );

        return view('contact_view', $data);
    }
}
?>