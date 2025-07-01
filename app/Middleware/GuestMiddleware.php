<?php
namespace App\Middleware;

use Core\Middleware;
use Core\Auth;

class GuestMiddleware extends Middleware
{
    public function handle()
    {
        if (Auth::check()) {
            session()->flash('info', 'Zaten giriş yapmışsınız.');
            redirect('/dashboard');
        }
    }
}
