<?php
namespace App\Controllers;


class test extends BaseController
{
    public function hashing(){
        $password = "zai";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        echo $hashedPassword;
    }
}