<?php

namespace App\Http\Controllers;

use App\Enums\ClientStatusEnum;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $clients = Client::latest()->paginate(10);

        return view('client.index',compact('clients'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $id_status_new = ClientStatusEnum::NEW;

        return view('client.create', compact('id_status_new'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_country' => 'required|min:1|integer',
            'id_city' => 'required|min:1|integer',
            'phone_number' => 'required',
            'email' => 'required|email',
            'bdate' => 'required|date',
            'address' => '',
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'surname' => 'required|max:100',
            'manager_comment' => '',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $params = $request->all();

        $params['id_user_add'] = auth()->user()->id; // Кто создал запись

        if (!empty($request->image)) {
            // *** Upload client photo:
            $photoName = time().'.'.$request->image->extension();

            if (!File::exists(storage_path('app/images/clients'))) {
                File::makeDirectory(storage_path('app/images/clients'), 0777, true);
            }

            $request->image->storeAs('images/clients', $photoName);

            if (!empty($photoName)) {
                $params = array_merge($params, ['photo_name' => $photoName]);
            }
            // ***
        }

        $createClient = Client::create($params);

        if ($createClient) {
            return redirect()->route('clients.index')->with('success','Клиент успешно создан.');
        } else {
            return redirect()->route('clients.index')->with('error','Ошибка! Клиент не создан.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
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
    public function edit($id): View
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
    public function update(Request $request, Client $client): RedirectResponse
    {
        $request->validate([
            'id_status' => 'required|min:1|integer',
            'id_country' => 'required|min:1|integer',
            'id_city' => 'required|min:1|integer',
            'phone_number' => 'required',
            'email' => 'required|email',
            'bdate' => 'required|date',
            'address' => '',
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'surname' => 'required|max:100',
            'manager_comment' => '',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $params = $request->all();

        $params['id_user_update'] = auth()->user()->id; // Кто редактировал запись

        $oldPhotoName = $request->get('photo_name');

        if (!empty($request->image)) {
            // *** Upload client photo:
            $photoName = time().'.'.$request->image->extension();

            if (!file_exists(storage_path('app/public/images/clients'))) {
                //mkdir(storage_path('app/images/clients'));
            }

            $uploadPhoto = $request->image->storeAs('public/images/clients', $photoName);

            if ($uploadPhoto && !empty($photoName)) {
                $params = array_merge($params, ['photo_name' => $photoName]);
            }
        // ***
        }

        $updateClient = $client->update($params);

        if ($updateClient) {
            // *** Проверка наличия файла и его удаление, если он существует:
            $oldPhotoPath = storage_path('app/public/images/clients/' . $oldPhotoName);

            if (!empty($photoName) && File::exists($oldPhotoPath)) {
                File::delete($oldPhotoPath); // Удаление файла
            }
            // ***

            return redirect()->route('clients.index')
                ->with('success','Данные клиента успешно обновлены.');
        } else {
            return redirect()->route('clients.index')
                ->with('error','Ошибка! Данные клиента не обновлены.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $client = Client::query()->findOrFail($id);

        $deleteClient = $client->delete();

        if ($deleteClient) {
            return redirect()->route('clients.index')
                ->with('success','Клиент успешно удалён.');
        } else {
            return redirect()->route('clients.index')
                ->with('error','Ошибка! Клиент не удалён.');
        }

    }
}
