<?php

namespace App\Http\Controllers\Client;

use App\DataTransferObjects\Client\ClientStoreDTO;
use App\DataTransferObjects\Client\ClientUpdateDTO;
use App\Enums\ClientStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Models\Client;
use App\Services\ClientService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $clients = Client::latest()->paginate(10);

        return view('client.index',compact('clients'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $id_status_new = ClientStatusEnum::NEW->value;

        return view('client.create', compact('id_status_new'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ClientStoreRequest $clientStoreRequest
     * @param ClientService $clientService
     * @return RedirectResponse|JsonResponse
     */
    public function store(ClientStoreRequest $clientStoreRequest, ClientService $clientService): RedirectResponse|JsonResponse
    {
        try {
            $clientStoreDTO = new ClientStoreDTO($clientStoreRequest);

            $createClient = $clientService->processStore($clientStoreDTO);

            if ($createClient) {
                return redirect()->route('clients.index')->with('success','Клиент успешно создан.');
            }

            return redirect()->route('clients.create')->with('error','Ошибка! Клиент не создан.');
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|JsonResponse
     */
    public function show($id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|JsonResponse
    {
        try {
            $client = Client::query()->findOrFail($id);

            $clientPhotoPath = '';

            if (!empty($client->photo_name) && File::exists(storage_path('app/public/images/clients/'.$client->photo_name))) {
                $clientPhotoPath = 'storage/images/clients/'.$client->photo_name;
            }

            return view('client.show',compact(['client', 'clientPhotoPath']));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|JsonResponse
     */
    public function edit($id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|JsonResponse
    {
        try {
            $client = Client::query()->findOrFail($id);

            $clientPhotoPath = '';

            if (!empty($client->photo_name) && File::exists(storage_path('app/public/images/clients/'.$client->photo_name))) {
                $clientPhotoPath = 'storage/images/clients/'.$client->photo_name;
            }

            $statuses_list_data = ClientStatusEnum::getStatusesList();

            return view('client.edit',compact(['client', 'clientPhotoPath', 'statuses_list_data',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClientUpdateRequest $clientUpdateRequest
     * @param $id
     * @param ClientService $clientService
     * @return RedirectResponse|JsonResponse
     */
    public function update(ClientUpdateRequest $clientUpdateRequest, $id, ClientService $clientService): RedirectResponse|JsonResponse
    {
        try {
            $clientUpdateDTO = new ClientUpdateDTO($clientUpdateRequest, (int) $id);

            $updateClient = $clientService->processUpdate($clientUpdateDTO);

            if ($updateClient) {
                return redirect()->route('clients.index')->with('success','Данные клиента успешно обновлены.');
            }

            return redirect()->route('clients.edit')->with('error','Ошибка! Данные клиента не обновлены.');
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
            $client = Client::query()->findOrFail($id);

            $deleteClient = $client->delete();

            if ($deleteClient) {
                return redirect()->route('clients.index')->with('success','Клиент успешно удалён.');
            }

            return redirect()->route('clients.index')->with('error','Ошибка! Клиент не удалён.');
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
