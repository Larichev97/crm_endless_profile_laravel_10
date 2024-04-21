<?php

namespace App\Http\Controllers\Admin\Auth;

// use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Models\User;

final class AdminRegisterController extends Controller
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

        $user = User::query()->create($attributes);

        auth()->login($user);

        return redirect()->route('admin.dashboard');
    }
}
