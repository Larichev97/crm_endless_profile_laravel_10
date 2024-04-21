<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

final class AdminResetPassword extends Controller
{
    use Notifiable;

    public function show()
    {
        abort(404); // Временно отключаю возможность восстановить пароль

        return view('auth.reset-password');
    }

    public function routeNotificationForMail() {
        abort(404); // Временно отключаю возможность восстановить пароль

        return request()->email;
    }

    public function send(Request $request)
    {
        abort(404); // Временно отключаю возможность восстановить пароль

        $email = $request->validate([
            'email' => ['required']
        ]);

        $user = User::query()->where('email', $email)->first();

        if ($user) {
            $this->notify(new ForgotPassword($user->id));
            return back()->with('success', 'An email was send to your email address');
        }
    }
}
