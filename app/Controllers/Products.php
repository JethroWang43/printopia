<?php
namespace App\Controllers;

class Products extends BaseController {
    public function index() {
        $data = array(
            'title' => 'Printopia - Products'
        );

        return view('product_view', $data);
    }
}
?>
