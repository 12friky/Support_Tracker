<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSetting;

class SettingsController extends Controller
{
    public function index()
    {
        $userId = session('staff_user_id');
        $user = $userId ? User::find($userId) : null;
        $settings = $user
            ? UserSetting::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'email_notifications' => true,
                    'sms_alerts' => false,
                    'auto_save_drafts' => true,
                    'timezone' => 'GMT',
                    'language' => 'English',
                ]
            )
            : null;

        return view('settings.index', compact('user', 'settings'));
    }
}
