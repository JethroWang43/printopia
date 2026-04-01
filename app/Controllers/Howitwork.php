<?php
namespace App\Controllers;

class Howitwork extends BaseController {
    public function index() {
        $data = array(
            'title' => 'Printopia - How It Works'
        );

        return view('howitwork_view', $data);
    }
}
?>