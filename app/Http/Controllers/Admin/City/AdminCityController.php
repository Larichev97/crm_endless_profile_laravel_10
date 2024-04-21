<?php

namespace App\Http\Controllers\Admin\City;

use App\DataTransferObjects\City\CityStoreDTO;
use App\DataTransferObjects\City\CityUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\City\CityStoreRequest;
use App\Http\Requests\City\CityUpdateRequest;
use App\Repositories\City\CityRepository;
use App\Repositories\Country\CountryRepository;
use App\Services\CrudActionsServices\CityService;
use App\Services\FilterTableService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AdminCityController extends Controller
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
     * @param FilterTableService $filterTableService
     * @return \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
     */
    public function index(Request $request, FilterTableService $filterTableService,): \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
    {
        try {
            $page = (int) $request->get(key: 'page', default: 1);
            $sortBy = $request->get(key: 'sort_by', default: 'id');
            $sortWay = $request->get(key: 'sort_way', default: 'desc');

            $filterFieldsArray = $filterTableService->processPrepareFilterFieldsArray(allFieldsData: $request->all());
            $filterFieldsObject = $filterTableService->processConvertFilterFieldsToObject(filterFieldsArray: $filterFieldsArray);

            $cities = $this->cityRepository->getAllWithPaginate(perPage: 10, page: $page, orderBy: $sortBy, orderWay: $sortWay, useCache: true, filterFieldsData: $filterFieldsArray);
            $displayedFields = $this->cityRepository->getDisplayedFieldsOnIndexPage();

            $countriesListData = $this->countryRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);

            return view(view: 'city.index', data: compact(['cities', 'displayedFields', 'filterFieldsObject', 'sortBy', 'sortWay', 'countriesListData',]));
        } catch (Exception $exception) {
            return response()->json(data: ['error' => $exception->getMessage()], status: 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $countriesListData = $this->countryRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);

        return view(view: 'city.create', data: compact(['countriesListData',]));
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
            $cityStoreDTO = new CityStoreDTO(cityStoreRequest: $cityStoreRequest);

            $createCity = $this->cityService->processStore(cityStoreDTO: $cityStoreDTO);

            if ($createCity) {
                return redirect()->route(route: 'admin.cities.index')->with(key: 'success', value: 'Город успешно создан.');
            }

            return back()->with(key: 'error', value: 'Ошибка! Город не создан.')->withInput();
        } catch (Exception $exception) {
            return response()->json(data: ['error' => $exception->getMessage()], status: 401);
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
            $city = $this->cityRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($city)) {
                abort(code: 404);
            }

            return view(view: 'city.show', data: compact(['city',]));
        } catch (Exception $exception) {
            return response()->json(data: ['error' => $exception->getMessage()], status: 401);
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
            $city = $this->cityRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($city)) {
                abort(code: 404);
            }

            $countriesListData = $this->countryRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);

            return view(view: 'city.edit', data: compact(['city', 'countriesListData',]));
        } catch (Exception $exception) {
            return response()->json(data: ['error' => $exception->getMessage()], status: 401);
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
            $cityUpdateDTO = new CityUpdateDTO(cityUpdateRequest: $cityUpdateRequest, id_city: (int) $id);

            $updateCity = $this->cityService->processUpdate(cityUpdateDTO: $cityUpdateDTO, cityRepository: $this->cityRepository);

            if ($updateCity) {
                return redirect()->route(route: 'admin.cities.index')->with(key: 'success', value: sprintf('Данные города #%s успешно обновлены.', $id));
            }

            return back()->with(key: 'error', value: sprintf('Ошибка! Данные города #%s не обновлены.', $id))->withInput();
        } catch (Exception $exception) {
            return response()->json(data: ['error' => $exception->getMessage()], status: 401);
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
            $deleteCity = $this->cityService->processDestroy(id: $id, cityRepository: $this->cityRepository);

            if ($deleteCity) {
                return redirect()->route(route: 'admin.cities.index')->with(key: 'success', value: sprintf('Город #%s успешно удален.', $id));
            }

            return back()->with(key: 'error', value: sprintf('Ошибка! Город #%s не удален.', $id));
        } catch (Exception $exception) {
            return response()->json(data: ['error' => $exception->getMessage()], status: 401);
        }
    }
}
