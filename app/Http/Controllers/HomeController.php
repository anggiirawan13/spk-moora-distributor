<?php

namespace App\Http\Controllers;

use App\Models\Distributor;

class HomeController extends Controller
{

    public function index()
    {
        $distributors = Distributor::latest()->get();
        return view('auth.login', compact('distributors'));
    }

    public function login()
    {
        return view('auth.login');
    }
    public function register()
    {
        return view('auth.register');
    }

    public function redirect()
    {
        return view('dashboard');
    }
}
