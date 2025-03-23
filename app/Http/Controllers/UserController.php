<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request){
        $users = User::all();
        return Inertia::render('User/List', [
            'users' => $users
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
}
