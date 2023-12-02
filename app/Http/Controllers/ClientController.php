<?php

namespace App\Http\Controllers;

use App\Enums\ClientStatusEnum;
use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
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
        $id_status_new = ClientStatusEnum::NEW;

        return view('client.create', compact('id_status_new'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientStoreRequest $request, ClientService $service): RedirectResponse
    {
        $createClient = $service->store($request);

        if ($createClient) {
            return redirect()->route('clients.index')->with('success','Клиент успешно создан.');
        }

        return redirect()->route('clients.create')->with('error','Ошибка! Клиент не создан.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $client = Client::query()->findOrFail($id);

        $clientPhotoPath = '';

        if (!empty($client->photo_name) && File::exists(storage_path('app/public/images/clients/'.$client->photo_name))) {
            $clientPhotoPath = 'storage/images/clients/'.$client->photo_name;
        }

        return view('client.show',compact(['client', 'clientPhotoPath']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $client = Client::query()->findOrFail($id);

        $clientPhotoPath = '';

        if (!empty($client->photo_name) && File::exists(storage_path('app/public/images/clients/'.$client->photo_name))) {
            $clientPhotoPath = 'storage/images/clients/'.$client->photo_name;
        }

        $statuses_list_data = ClientStatusEnum::getStatusesList();

        return view('client.edit',compact(['client', 'clientPhotoPath', 'statuses_list_data',]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdateRequest $clientUpdateRequest, Client $client, ClientService $service): RedirectResponse
    {
        $updateClient = $service->update($clientUpdateRequest, $client);

        if ($updateClient) {
            return redirect()->route('clients.index')->with('success','Данные клиента успешно обновлены.');
        }

        return redirect()->route('clients.edit')->with('error','Ошибка! Данные клиента не обновлены.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $client = Client::query()->findOrFail($id);

        $deleteClient = $client->delete();

        if ($deleteClient) {
            return redirect()->route('clients.index')->with('success','Клиент успешно удалён.');
        }

        return redirect()->route('clients.index')->with('error','Ошибка! Клиент не удалён.');
    }
}
