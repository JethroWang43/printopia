<?php
namespace App\Controllers;

class Employee extends BaseController {
    public function index() {
        $data = array(
            'title' => 'Printopia - Employee Dashboard'
        );

        return view('employee_view', $data);
    }
}
?>