<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function create()
    {
        abort(404); // Временно отключаю возможность регистрации

        return view('auth.register');
    }

    public function store()
    {
        abort(404); // Временно отключаю возможность регистрации

        $attributes = request()->validate([
            'username' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:5|max:255',
            'terms' => 'required'
        ]);
        $user = User::create($attributes);
        auth()->login($user);

        return redirect('/dashboard');
    }
}
