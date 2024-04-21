<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AdminChangePassword extends Controller
{
    protected $user;

    public function __construct()
    {
        Auth::logout();

        $id = intval(request()->id);
        $this->user = User::query()->find($id);
    }

    public function show(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('auth.change-password');
    }

    public function update(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $attributes = $request->validate([
            'email' => ['required'],
            'password' => ['required', 'min:5'],
            'confirm-password' => ['same:password']
        ]);

        $existingUser = User::query()->where('email', $attributes['email'])->first();

        if ($existingUser) {
            $existingUser->update([
                'password' => $attributes['password']
            ]);

            return redirect()->route('admin.login');
        } else {
            return back()->with('error', 'Your email does not match the email who requested the password change');
        }
    }
}
