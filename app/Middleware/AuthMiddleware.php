<?php
namespace App\Middleware;

use Core\Middleware;
use Core\Auth;

class AuthMiddleware extends Middleware
{
    public function handle()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Bu sayfaya erişmek için giriş yapmalısınız.');
            redirect('/auth/login');
        }
    }
}
