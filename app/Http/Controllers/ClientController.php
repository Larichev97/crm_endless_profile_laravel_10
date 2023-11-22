<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

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
        return view('client.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_status' => 'required|min:1|integer',
            'id_country' => 'required|min:1|integer',
            'id_city' => 'required|min:1|integer',
            'phone_number' => 'required|min:10|max:13|numeric',
            'email' => 'required|email',
            'bdate' => 'required|date',
            'address' => '',
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'surname' => 'required|max:100',
            'manager_comment' => '',
        ]);

        Client::create($request->all());

        return redirect()->route('client.index')
            ->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): View
    {
        return view('client.show',compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client): View
    {
        return view('client.edit',compact('client'));
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
            'phone_number' => 'required|min:10|max:13|numeric',
            'email' => 'required|email',
            'bdate' => 'required|date',
            'address' => '',
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'surname' => 'required|max:100',
            'manager_comment' => '',
        ]);

        $client->update($request->all());

        return redirect()->route('client.index')
            ->with('success','Данные клиента успешно обновлены.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('client.index')
            ->with('success','Клиент успешно удалён.');
    }
}
