<?php

namespace App\Http\Controllers\Client;

use App\DataTransferObjects\Client\ClientStoreDTO;
use App\DataTransferObjects\Client\ClientUpdateDTO;
use App\Enums\ClientStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Repositories\Client\ClientRepository;
use App\Services\ClientService;
use App\Services\FileService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    /**
     * @param FileService $fileService
     * @param ClientService $clientService
     * @param ClientRepository $clientRepository
     * @param string $publicDirPath
     */
    public function __construct(
        readonly FileService $fileService,
        readonly ClientService $clientService,
        readonly ClientRepository $clientRepository,
        readonly string $publicDirPath = 'images/clients'
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
    {
        try {
            $clients = $this->clientRepository->getAllWithPaginate(10);

            return view('client.index',compact('clients'))->with('i', (request()->input('page', 1) - 1) * 5);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $id_status_new = ClientStatusEnum::NEW->value;

        return view('client.create', compact('id_status_new'));
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
            $clientStoreDTO = new ClientStoreDTO($clientStoreRequest);

            $createClient = $this->clientService->processStore($clientStoreDTO);

            if ($createClient) {
                return redirect()->route('clients.index')->with('success','Клиент успешно создан.');
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
            $client = $this->clientRepository->getForEditModel($id);

            if (empty($client)) {
                abort(404);
            }

            $clientPhotoPath = $this->fileService->processGetPublicFilePath((string) $client->photo_name, $this->publicDirPath);

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
            $client = $this->clientRepository->getForEditModel($id);

            $clientPhotoPath = $this->fileService->processGetPublicFilePath((string) $client->photo_name, $this->publicDirPath);

            $statusesListData = ClientStatusEnum::getStatusesList();

            return view('client.edit',compact(['client', 'clientPhotoPath', 'statusesListData',]));
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
            $clientUpdateDTO = new ClientUpdateDTO($clientUpdateRequest, (int) $id);

            $updateClient = $this->clientService->processUpdate($clientUpdateDTO, $this->clientRepository);

            if ($updateClient) {
                return redirect()->route('clients.index')->with('success', sprintf('Данные клиента #%s успешно обновлены.', $id));
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
            $client = $this->clientRepository->getForEditModel($id);

            if (empty($client)) {
                return back()->with('error', sprintf('Ошибка! Клиент #%s не удалён.', $id));
            }

            $deleteClient = $client->delete();

            if ($deleteClient) {
                return redirect()->route('clients.index')->with('success','Клиент успешно удалён.');
            }

            return back()->with('error', sprintf('Ошибка! Клиент #%s не удалён.', $id));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
