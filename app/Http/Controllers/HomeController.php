<?php

namespace App\Http\Controllers;

use App\Repositories\Client\ClientRepository;
use App\Repositories\QrProfile\QrProfileRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\View\Factory;

class HomeController extends Controller
{
    public function __construct(
        readonly ClientRepository $clientRepository,
        readonly QrProfileRepository $qrProfileRepository
    )
    {
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     */
    public function index():Application|View|Factory
    {
        $clientsTotalCount = count($this->clientRepository->getModelCollection(true));
        $qrProfilesTotalCount = count($this->qrProfileRepository->getModelCollection(true));

        return view('pages.dashboard', compact(['clientsTotalCount', 'qrProfilesTotalCount']));
    }
}
