<?php

namespace App\Http\Controllers\Admin\UserProfile;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class AdminUserProfileController extends Controller
{
    public function show(): View|Factory|\Illuminate\Foundation\Application|Application
    {
        return view('pages.user-profile');
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function update(Request $request, UserRepository $userRepository): Factory|View|\Illuminate\Foundation\Application|Application|RedirectResponse
    {
        try {
            $request->validate([
                'username' => ['required','max:255', 'min:2'],
                'firstname' => ['max:100'],
                'lastname' => ['max:100'],
                'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore(auth()->user()->id),],
                'address' => ['max:100'],
                'city' => ['max:100'],
                'country' => ['max:100'],
                'postal' => ['max:100'],
                'about' => ['max:255']
            ]);

            $updateUser = auth()->user()->update([
                'username' => $request->get('username'),
                'firstname' => $request->get('firstname'),
                'lastname' => $request->get('lastname'),
                'email' => $request->get('email') ,
                'address' => $request->get('address'),
                'city' => $request->get('city'),
                'country' => $request->get('country'),
                'postal' => $request->get('postal'),
                'about' => $request->get('about')
            ]);

            if ($updateUser) {
                $userRepository->cleanCache();

                return back()->with('success', 'Данные профиля успешно обновлены.');
            }

            return back()->with('error', 'Ошибка! Данные профиля не обновлены.')->withInput();
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }
}
