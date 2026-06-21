<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;

class SettingsController extends Controller
{
    public function index()
    {
        $userId = session('staff_user_id');

        $settings = $userId
            ? UserSetting::firstOrCreate(
                ['user_id' => $userId],
                [
                    'email_notifications' => true,
                    'sms_alerts'          => false,
                    'auto_save_drafts'    => true,
                    'timezone'            => 'GMT',
                    'language'            => 'English',
                ]
            )
            : null;

        return view('settings.index', compact('settings'));
    }
}
