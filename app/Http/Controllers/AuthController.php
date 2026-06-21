<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     * Redirects already-authenticated users straight to the dashboard.
     */
    public function showLogin()
    {
        if (session('staff_logged_in')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Authenticate the staff member by Staff ID and password.
     * Stores essential identity data in the session on success.
     */
    public function login(Request $request)
    {
        $request->validate([
            'staff_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('staff_id', trim($request->staff_id))->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session()->regenerate();

            session()->put('staff_logged_in', true);
            session()->put('staff_id',        $user->staff_id);
            session()->put('staff_user_id',   $user->id);
            session()->put('staff_user_name', $user->name);
            session()->put('staff_user_role', $user->role);

            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors(['login' => 'Invalid Staff ID or password.'])
            ->withInput(['staff_id' => $request->staff_id]);
    }

    /**
     * Log the staff member out by flushing the session entirely.
     */
    public function logout()
    {
        session()->flush();

        return redirect()->route('login');
    }
}
