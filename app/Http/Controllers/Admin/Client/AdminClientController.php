<?php

namespace App\Http\Controllers\Admin\Client;

use App\DataTransferObjects\Client\ClientStoreDTO;
use App\DataTransferObjects\Client\ClientUpdateDTO;
use App\Enums\ClientStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Models\Client;
use App\Repositories\City\CityRepository;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Country\CountryRepository;
use App\Services\CrudActionsServices\ClientService;
use App\Services\FileService;
use App\Services\FilterTableService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AdminClientController extends Controller
{
    /**
     * @param FileService $fileService
     * @param ClientService $clientService
     * @param ClientRepository $clientRepository
     * @param CountryRepository $countryRepository
     * @param CityRepository $cityRepository
     * @param string $publicDirPath
     */
    public function __construct(
        readonly FileService $fileService,
        readonly ClientService $clientService,
        readonly ClientRepository $clientRepository,
        readonly CountryRepository $countryRepository,
        readonly CityRepository $cityRepository,
        readonly string $publicDirPath = 'images/clients'
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
    public function index(Request $request, FilterTableService $filterTableService): \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
    {
        try {
            $page = (int) $request->get(key: 'page', default: 1);
            $sortBy = $request->get(key: 'sort_by', default: 'id');
            $sortWay = $request->get(key: 'sort_way', default: 'desc');

            $filterFieldsArray = $filterTableService->processPrepareFilterFieldsArray(allFieldsData: $request->all());
            $filterFieldsObject = $filterTableService->processConvertFilterFieldsToObject(filterFieldsArray: $filterFieldsArray);

            $clients = $this->clientRepository->getAllWithPaginate(perPage: 10, page: $page, orderBy: $sortBy, orderWay: $sortWay);
            $displayedFields = $this->clientRepository->getDisplayedFieldsOnIndexPage();

            $statusesListData = ClientStatusEnum::getStatusesList();
            $countriesListData = $this->countryRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);
            $citiesListData = $this->cityRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);

            return view('client.index', compact(['clients', 'displayedFields','filterFieldsObject', 'statusesListData', 'countriesListData', 'citiesListData', 'sortBy', 'sortWay',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(Request $request): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $contactFormDataArray = $this->clientService->processGetContactFormData(request: $request);

        $idStatusNew = ClientStatusEnum::NEW->value;

        $countriesListData = $this->countryRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);
        $citiesListData = $this->cityRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);

        return view('client.create', compact('idStatusNew', 'countriesListData', 'citiesListData'), $contactFormDataArray);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ClientStoreRequest $clientStoreRequest
     * @return RedirectResponse|JsonResponse
     */
    public function store(ClientStoreRequest $clientStoreRequest): RedirectResponse|JsonResponse
    {
        try {
            $createClient = $this->clientService->processStore(dto: new ClientStoreDTO(clientStoreRequest: $clientStoreRequest));

            if ($createClient) {
                return redirect()->route('admin.clients.index')->with('success','Клиент успешно создан.');
            }

            return back()->with('error','Ошибка! Клиент не создан.')->withInput();
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
            $client = $this->clientRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($client)) {
                abort(404);
            }

            /** @var Client $client */

            $clientPhotoPath = $this->fileService->processGetPublicFilePath(fileName: $client->getPhotoName(), publicDirPath: $this->publicDirPath);

            return view('client.show',compact(['client', 'clientPhotoPath']));
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
            $client = $this->clientRepository->getForEditModel((int) $id, true);

            if (empty($client)) {
                abort(404);
            }

            /** @var Client $client */

            $clientPhotoPath = $this->fileService->processGetPublicFilePath(fileName: $client->getPhotoName(), publicDirPath: $this->publicDirPath);

            $statusesListData = ClientStatusEnum::getStatusesList();
            $countriesListData = $this->countryRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);
            $citiesListData = $this->cityRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);

            return view('client.edit',compact(['client', 'clientPhotoPath', 'statusesListData', 'countriesListData', 'citiesListData',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClientUpdateRequest $clientUpdateRequest
     * @param $id
     * @return RedirectResponse|JsonResponse
     */
    public function update(ClientUpdateRequest $clientUpdateRequest, $id): RedirectResponse|JsonResponse
    {
        try {
            $updateClient = $this->clientService->processUpdate(dto: new ClientUpdateDTO(clientStoreRequest: $clientUpdateRequest, id_client: (int) $id), repository: $this->clientRepository);

            if ($updateClient) {
                return redirect()->route('admin.clients.index')->with('success', sprintf('Данные клиента #%s успешно обновлены.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Данные клиента #%s не обновлены.', $id))->withInput();
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
            $deleteClient = $this->clientService->processDestroy(id: $id, repository: $this->clientRepository);

            if ($deleteClient) {
                return redirect()->route('admin.clients.index')->with('success', sprintf('Клиент #%s успешно удалён.', $id));
            }

            return back()->with('error', sprintf('Ошибка! Клиент #%s не удалён.', $id));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
