<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $totalActivities = Activity::count();
        $completed       = Activity::where('status', 'Done')->count();
        $pending         = Activity::where('status', 'Pending')->count();
        $updatesToday    = ActivityLog::whereDate('created_at', today())->count();

        $recentUpdates = ActivityLog::with(['activity', 'updatedBy'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalActivities',
            'completed',
            'pending',
            'updatesToday',
            'recentUpdates'
        ));
    }
}
