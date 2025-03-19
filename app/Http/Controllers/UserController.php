<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;

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
}
