<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Single query for activity counts grouped by status
        $activityCounts = Activity::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalActivities = array_sum($activityCounts->toArray());
        $completed       = $activityCounts->get('Done', 0);
        $pending         = $activityCounts->get('Pending', 0);

        // Today's log count
        $updatesToday = ActivityLog::whereDate('created_at', today())->count();

        // Five most recent updates logged today
        $recentUpdates = ActivityLog::with(['activity', 'updatedBy'])
            ->whereDate('created_at', today())
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
