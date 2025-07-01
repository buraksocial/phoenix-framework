<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function dashboard()
    {
        $user = Auth::user();
        return view('dashboard', ['user' => $user]);
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }
}
