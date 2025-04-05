<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');
        $users = User::query()
            ->when($search, function ($query, $search){
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    
        /* dd($users); */
        return Inertia::render('User/List', [
            'users' => $users,
            'filters' => [
                'search' => $search
            ]
        ]);
    }

    public function create(Request $request){
        return Inertia::render('User/New', [
        ]);
    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => ['required', 'min:8', 'confirmed'],
                'password_confirmation' => 'required'
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('user.index')
            ->with('success', 'Usuario registrado');
            } catch (\Illuminate\Validation\ValidationException $e) {
                return back()->withErrors($e->errors())->withInput();
            } catch (\Exception $e) {
                return back()->with('error', 'Error al crear el usuario')->withInput();
            }
    }

    public function edit($id){
        $user = User::where('id', $id)->first();
        if(!$user){
            return redirect()->route('user.index')->with('error', 'Usuario no encontrado');
        }
        return Inertia::render('User/Edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request){
        try {
            $rules = [
                'id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $request->id,
            ];

            if ($request->filled('password')) {
                $rules['password'] = ['required', 'min:8', 'confirmed'];
                $rules['password_confirmation'] = 'required';
            }

            $validated = $request->validate($rules);
            $user = User::findOrFail($validated['id']);

            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return redirect()->route('user.index')
                ->with('success', 'Usuario actualizado correctamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el usuario')->withInput();
        }
    }

    public function delete($id){
        try {
            $user = User::where('id', $id)->first();

            if ($user->id === auth()->id()) {
                return back()->with('error', 'No puedes eliminar este usuario');
            }

            $user->delete();

            return redirect()->route('user.index')
                ->with('success', 'Usuario eliminado correctamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el usuario')->withInput();
        }
    }
}
