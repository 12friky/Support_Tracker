<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $userId = session('staff_user_id');
        $user = $userId ? User::find($userId) : null;
        $recentActivities = $user
            ? ActivityLog::where('updated_by_id', $user->id)
                ->with('activity')
                ->latest()
                ->take(5)
                ->get()
            : collect();

        return view('profile.index', compact('user', 'recentActivities'));
    }
}
