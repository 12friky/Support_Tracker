<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;

class SupportTrackerSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'staff_id' => '123456',
                'name' => 'Francis Ngumah',
                'email' => 'francis@support.com',
                'password' => bcrypt('1234'),
                'role' => 'Admin',
                'department' => 'Infrastructure',
                'phone' => '+233 54 789 1122',
                'location' => 'Accra, Ghana',
                'shift' => '8:00 AM - 5:00 PM',
                'is_active' => true,
            ],
            [
                'staff_id' => '100001',
                'name' => 'Jane Doe',
                'email' => 'jane@support.com',
                'password' => bcrypt('password'),
                'role' => 'User',
                'department' => 'Operations',
                'phone' => '+233 20 111 2233',
                'location' => 'Accra, Ghana',
                'shift' => '9:00 AM - 6:00 PM',
                'is_active' => true,
            ],
            [
                'staff_id' => '100002',
                'name' => 'John Smith',
                'email' => 'john@support.com',
                'password' => bcrypt('password'),
                'role' => 'User',
                'department' => 'Operations',
                'phone' => '+233 24 555 6677',
                'location' => 'Kumasi, Ghana',
                'shift' => '8:30 AM - 5:30 PM',
                'is_active' => true,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['staff_id' => $data['staff_id']],
                $data
            );
        }

        $francis = User::where('staff_id', '123456')->first();
        $jane = User::where('staff_id', '100001')->first();
        $john = User::where('staff_id', '100002')->first();

        $activities = [
            [
                'name' => 'Server Restart',
                'description' => 'Scheduled server restart for maintenance',
                'status' => 'Pending',
                'assigned_to_id' => $jane?->id,
                'created_by_id' => $francis?->id,
                'last_updated_by_id' => $francis?->id,
            ],
            [
                'name' => 'Email Ticket Follow-up',
                'description' => 'Follow up on pending client email tickets',
                'status' => 'Done',
                'assigned_to_id' => $john?->id,
                'created_by_id' => $francis?->id,
                'last_updated_by_id' => $john?->id,
            ],
            [
                'name' => 'Database Backup',
                'description' => 'Full backup of production database',
                'status' => 'Done',
                'assigned_to_id' => $francis?->id,
                'created_by_id' => $francis?->id,
                'last_updated_by_id' => $francis?->id,
            ],
        ];

        foreach ($activities as $activityData) {
            $activity = Activity::updateOrCreate(
                ['name' => $activityData['name']],
                $activityData
            );

            ActivityLog::updateOrCreate(
                [
                    'activity_id' => $activity->id,
                    'status' => $activity->status,
                ],
                [
                    'remark' => $activity->status === 'Done'
                        ? 'Completed successfully.'
                        : 'Awaiting follow-up.',
                    'updated_by_id' => $activity->last_updated_by_id,
                ]
            );
        }

        if ($francis) {
            UserSetting::updateOrCreate(
                ['user_id' => $francis->id],
                [
                    'email_notifications' => true,
                    'sms_alerts' => false,
                    'auto_save_drafts' => true,
                    'timezone' => 'GMT',
                    'language' => 'English',
                ]
            );
        }
    }
}