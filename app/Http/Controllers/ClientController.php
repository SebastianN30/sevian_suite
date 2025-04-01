<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::query()
            ->latest()
            ->paginate(8);
        return Inertia::render('Client/List', [
            'clients' => $clients
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Client/New', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return Inertia::render('Client/Edit', [
            'client' => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:clients'],
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'integer', 'max_digits:10'],
            'email' => ['required', 'email'],
            'address' => ['nullable', 'string', 'max:255']
        ]);

        $client = Client::find($request->id);

        $client->fill([
            'id' => $request->id,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'address' => $request->address
        ]);

        if (!$client->isDirty()) {
            return redirect()->back()->withErrors(['error' => 'Cliente no modificado.']);
        }

        $client->save();

        return redirect()->route('client.index')->with('success', 'Cliente actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
