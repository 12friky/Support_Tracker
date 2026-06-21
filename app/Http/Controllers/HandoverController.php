<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HandoverController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date
            ? Carbon::parse($request->date)
            : now();

        // Requirement: show the LATEST remark per activity for the selected day.
        // We get all logs for the day, then keep only the most recent entry per activity.
        $handovers = ActivityLog::with(['activity', 'updatedBy'])
            ->whereDate('created_at', $date)
            ->latest()
            ->get()
            ->groupBy('activity_id')          // group by activity
            ->map(fn($group) => $group->first()) // keep only the latest log per activity
            ->values()
            ->map(function (ActivityLog $log) {
                $person = $log->updatedBy;
                return (object) [
                    'id'                => $log->id,
                    'activity_id'       => $log->activity?->id,
                    'activity_name'     => $log->activity?->name ?? 'Unknown Activity',
                    'status'            => $log->status,
                    'remark'            => $log->remark ?? '-',
                    'updated_by'        => $person?->name ?? 'System',
                    'updated_at'        => $log->created_at->format('h:i A'),
                    'log_date'          => $log->created_at->format('l, j F Y'), // for date grouping header
                    'person_staff_id'   => $person?->staff_id,
                    'person_department' => $person?->department,
                    'person_shift'      => $person?->shift,
                    'person_phone'      => $person?->phone,
                ];
            });

        $stats = [
            'total'     => $handovers->count(),
            'completed' => $handovers->where('status', 'Done')->count(),
            'pending'   => $handovers->where('status', 'Pending')->count(),
        ];

        return view('handover.index', compact('date', 'handovers', 'stats'));
    }

    /**
     * Export handover entries for a given date as CSV.
     */
    public function export(Request $request)
    {
        $date = $request->date
            ? Carbon::parse($request->date)
            : now();

        $logs = ActivityLog::with(['activity', 'updatedBy'])
            ->whereDate('created_at', $date)
            ->latest()
            ->get();

        $filename = 'handover-' . $date->toDateString() . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Activity', 'Status', 'Remark', 'Updated By', 'Updated At']);
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->activity?->name ?? 'Unknown',
                    $log->status,
                    $log->remark ?? '-',
                    $log->updatedBy?->name ?? 'System',
                    $log->created_at->format('h:i A'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
