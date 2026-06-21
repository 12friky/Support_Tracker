<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupportTrackerSeeder extends Seeder
{
    public function run(): void
    {
        // ─── USERS ────────────────────────────────────────────────────────────
        // Three distinct support team members with different roles & passwords

        $users = [
            [
                'staff_id'   => '100001',
                'name'       => 'Francis Ngumah',
                'email'      => 'francis@support.com',
                'password'   => Hash::make('Admin@1234'),
                'role'       => 'Admin',
                'department' => 'Infrastructure & Platforms',
                'phone'      => '+233 54 789 1122',
                'location'   => 'Accra, Ghana',
                'shift'      => '08:00 AM – 05:00 PM',
                'is_active'  => true,
            ],
            [
                'staff_id'   => '100002',
                'name'       => 'Abena Mensah',
                'email'      => 'abena@support.com',
                'password'   => Hash::make('Support@5678'),
                'role'       => 'Support Engineer',
                'department' => 'Applications Support',
                'phone'      => '+233 20 111 2233',
                'location'   => 'Accra, Ghana',
                'shift'      => '09:00 AM – 06:00 PM',
                'is_active'  => true,
            ],
            [
                'staff_id'   => '100003',
                'name'       => 'Kwame Asante',
                'email'      => 'kwame@support.com',
                'password'   => Hash::make('Engineer@9012'),
                'role'       => 'Support Engineer',
                'department' => 'Operations',
                'phone'      => '+233 24 555 6677',
                'location'   => 'Kumasi, Ghana',
                'shift'      => '08:30 AM – 05:30 PM',
                'is_active'  => true,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['staff_id' => $data['staff_id']],
                $data
            );
        }

        $francis = User::where('staff_id', '100001')->first();
        $abena   = User::where('staff_id', '100002')->first();
        $kwame   = User::where('staff_id', '100003')->first();

        // ─── ACTIVITIES ───────────────────────────────────────────────────────

        $activities = [
            [
                'name'               => 'Daily SMS Count vs Log Comparison',
                'description'        => 'Compare the daily outbound SMS count against the SMS gateway logs to identify discrepancies.',
                'status'             => 'Done',
                'assigned_to_id'     => $abena?->id,
                'created_by_id'      => $francis?->id,
                'last_updated_by_id' => $abena?->id,
            ],
            [
                'name'               => 'Database Backup Verification',
                'description'        => 'Verify that the nightly database backup completed successfully and validate backup integrity.',
                'status'             => 'Done',
                'assigned_to_id'     => $kwame?->id,
                'created_by_id'      => $francis?->id,
                'last_updated_by_id' => $kwame?->id,
            ],
            [
                'name'               => 'Application Server Health Check',
                'description'        => 'Check CPU, memory, and disk usage on all application servers and report any anomalies.',
                'status'             => 'Pending',
                'assigned_to_id'     => $abena?->id,
                'created_by_id'      => $francis?->id,
                'last_updated_by_id' => $francis?->id,
            ],
            [
                'name'               => 'Email Queue Monitoring',
                'description'        => 'Monitor the email queue for failed or stuck jobs and resolve any delivery failures.',
                'status'             => 'Pending',
                'assigned_to_id'     => $kwame?->id,
                'created_by_id'      => $francis?->id,
                'last_updated_by_id' => $francis?->id,
            ],
            [
                'name'               => 'API Endpoint Response Time Audit',
                'description'        => 'Run response-time tests against all public-facing API endpoints and log results.',
                'status'             => 'Done',
                'assigned_to_id'     => $francis?->id,
                'created_by_id'      => $francis?->id,
                'last_updated_by_id' => $francis?->id,
            ],
        ];

        foreach ($activities as $actData) {
            $activity = Activity::updateOrCreate(
                ['name' => $actData['name']],
                $actData
            );

            // Create an activity log entry for each activity
            ActivityLog::updateOrCreate(
                ['activity_id' => $activity->id, 'status' => $activity->status],
                [
                    'remark'        => $activity->status === 'Done'
                        ? 'Completed successfully. No issues found.'
                        : 'Work in progress — pending handover to next shift.',
                    'updated_by_id' => $activity->last_updated_by_id,
                ]
            );
        }

        // ─── USER SETTINGS ────────────────────────────────────────────────────

        $settingsDefaults = [
            'email_notifications' => true,
            'sms_alerts'          => false,
            'auto_save_drafts'    => true,
            'timezone'            => 'GMT',
            'language'            => 'English',
        ];

        foreach ([$francis, $abena, $kwame] as $user) {
            if ($user) {
                UserSetting::updateOrCreate(
                    ['user_id' => $user->id],
                    $settingsDefaults
                );
            }
        }
    }
}
