<?php

namespace App\Http\Controllers\City;

use App\DataTransferObjects\City\CityStoreDTO;
use App\DataTransferObjects\City\CityUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\City\CityStoreRequest;
use App\Http\Requests\City\CityUpdateRequest;
use App\Repositories\City\CityRepository;
use App\Repositories\Country\CountryRepository;
use App\Services\CrudActionsServices\CityService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * @param CityService $cityService
     * @param CityRepository $cityRepository
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        readonly CityService $cityService,
        readonly CityRepository $cityRepository,
        readonly CountryRepository $countryRepository,
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
     */
    public function index(Request $request): \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
    {
        try {
            $cities = $this->cityRepository->getAllWithPaginate(10, (int) $request->get('page', 1), 'id', 'asc', true);

            return view('city.index',compact('cities'));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $countriesListData = $this->countryRepository->getForDropdownList('id', 'name', true);

        return view('city.create', compact(['countriesListData',]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CityStoreRequest $cityStoreRequest
     * @return RedirectResponse|JsonResponse
     */
    public function store(CityStoreRequest $cityStoreRequest): RedirectResponse|JsonResponse
    {
        try {
            $cityStoreDTO = new CityStoreDTO($cityStoreRequest);

            $createCity = $this->cityService->processStore($cityStoreDTO);

            if ($createCity) {
                return redirect()->route('cities.index')->with('success','Город успешно создан.');
            }

            return back()->with('error','Ошибка! Город не создан.')->withInput();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
     */
    public function show($id): Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
    {
        try {
            $city = $this->cityRepository->getForEditModel((int) $id, true);

            if (empty($city)) {
                abort(404);
            }

            return view('city.show',compact(['city',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
     */
    public function edit($id): Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
    {
        try {
            $city = $this->cityRepository->getForEditModel((int) $id, true);

            if (empty($city)) {
                abort(404);
            }

            $countriesListData = $this->countryRepository->getForDropdownList('id', 'name', true);

            return view('city.edit',compact(['city', 'countriesListData',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CityUpdateRequest $cityUpdateRequest
     * @param $id
     * @return RedirectResponse|JsonResponse
     */
    public function update(CityUpdateRequest $cityUpdateRequest, $id): RedirectResponse|JsonResponse
    {
        try {
            $cityUpdateDTO = new CityUpdateDTO($cityUpdateRequest, (int) $id);

            $updateCity = $this->cityService->processUpdate($cityUpdateDTO, $this->cityRepository);

            if ($updateCity) {
                return redirect()->route('cities.index')->with('success', sprintf('Данные города #%s успешно обновлены.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Данные города #%s не обновлены.', $id))->withInput();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse|JsonResponse
     */
    public function destroy($id): RedirectResponse|JsonResponse
    {
        try {
            $deleteCity = $this->cityService->processDestroy($id, $this->cityRepository);

            if ($deleteCity) {
                return redirect()->route('cities.index')->with('success', sprintf('Город #%s успешно удален.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Город #%s не удален.', $id));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
