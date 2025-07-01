<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Services\UserService;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login()
    {
        $email = request('email');
        $password = request('password');

        if ($this->userService->authenticate($email, $password)) {
            session()->flash('success', 'Başarıyla giriş yaptınız!');
            redirect('/dashboard');
        } else {
            session()->flash('error', 'E-posta veya şifre hatalı.');
            back();
        }
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register()
    {
        $name = request('name');
        $email = request('email');
        $password = request('password');
        $password_confirmation = request('password_confirmation');

        if ($password !== $password_confirmation) {
            session()->flash('error', 'Şifreler eşleşmiyor.');
            back();
        }

        try {
            $user = $this->userService->createUser($name, $email, $password);
            Auth::login($user);
            session()->flash('success', 'Kaydınız başarıyla oluşturuldu ve giriş yapıldı!');
            redirect('/dashboard');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            back();
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->flash('success', 'Başarıyla çıkış yaptınız.');
        redirect('/auth/login');
    }
}
