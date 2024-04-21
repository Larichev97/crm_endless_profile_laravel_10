<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ClientRepository;
use App\Repositories\ContactForm\ContactFormRepository;
use App\Repositories\QrProfile\QrProfileRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\View\Factory;

final class AdminDashboardController extends Controller
{
    public function __construct(
        readonly ContactFormRepository $contactFormRepository,
        readonly ClientRepository $clientRepository,
        readonly QrProfileRepository $qrProfileRepository
    )
    {
    }

    /**
     * Show the application dashboard.
     *
     */
    public function dashboard():Application|View|Factory
    {
        $contactFormsTotalCount = count($this->contactFormRepository->getModelCollection(true));
        $clientsTotalCount = count($this->clientRepository->getModelCollection(true));
        $qrProfilesTotalCount = count($this->qrProfileRepository->getModelCollection(true));

        return view('pages.dashboard', compact(['clientsTotalCount', 'qrProfilesTotalCount', 'contactFormsTotalCount']));
    }
}
