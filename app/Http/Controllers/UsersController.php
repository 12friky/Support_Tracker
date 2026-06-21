<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        $total = $users->count();
        $active = $users->where('is_active', true)->count();
        $admins = $users->where('role', 'Admin')->count();

        return view('users.index', compact('users', 'total', 'active', 'admins'));
    }
}
