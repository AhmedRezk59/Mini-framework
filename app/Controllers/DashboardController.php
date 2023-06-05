<?php

namespace App\Controllers;

class DashboardController
{
    public function index()
    {
        return view('welcome' , ['name' => 'Ahmed']);
    }
}
