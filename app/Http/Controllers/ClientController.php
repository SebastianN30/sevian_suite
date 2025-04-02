<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clients = Client::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Client/List', [
            'clients' => $clients,
            'filters' => [
                'search' => $search,
            ],
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'integer', 'max_digits:10'],
            'email' => ['required', 'email', 'unique:clients,email'],
            'address' => ['nullable', 'string', 'max:255']
        ]);

        Client::create([
            'name' => Str::title($request->name),
            'lastname' => Str::title($request->lastname),
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'address' => $request->address
        ]);

        return redirect()->route('client.index')->with('success', 'Cliente creado correctamente.');
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
    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'integer', 'max_digits:10'],
            'email' => ['required', 'email', Rule::unique('clients', 'email')->ignore($request->id)],
            'address' => ['nullable', 'string', 'max:255']
        ]);

        $client = Client::find($request->id);

        $client->fill([
            'name' => Str::title($request->name),
            'lastname' => Str::title($request->lastname),
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
    public function destroy($id)
    {
        Client::destroy($id);
        return redirect()->route('client.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
