<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered; 
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function create() 
    {
        return Inertia::render("Auth/");

    }

    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|string|in:admin,approver',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,

        ])
    }
}
