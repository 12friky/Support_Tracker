<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $userId = session('staff_user_id');
        $user   = $userId ? User::find($userId) : null;

        $recentActivities = $user
            ? ActivityLog::where('updated_by_id', $user->id)
                ->with('activity')
                ->latest()
                ->take(5)
                ->get()
            : collect();

        return view('profile.index', compact('user', 'recentActivities'));
    }

    /**
     * Update name, phone, and location.
     */
    public function update(Request $request)
    {
        $userId = session('staff_user_id');
        $user   = $userId ? User::find($userId) : null;

        if (!$user) {
            return redirect()->route('profile')->withErrors(['profile' => 'User not found.']);
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'location' => $request->location,
        ]);

        // Keep session name in sync
        session()->put('staff_user_name', $user->name);

        return redirect()->route('profile')
            ->with('profile_success', 'Profile updated successfully.');
    }

    /**
     * Change password after verifying the current one.
     */
    public function changePassword(Request $request)
    {
        $userId = session('staff_user_id');
        $user   = $userId ? User::find($userId) : null;

        if (!$user) {
            return redirect()->route('profile')->withErrors(['password' => 'User not found.']);
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile')
                ->withErrors(['password' => 'Current password is incorrect.'])
                ->with('open_password', true);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile')
            ->with('password_success', 'Password changed successfully.');
    }
}
