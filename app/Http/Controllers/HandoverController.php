<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\HandoverNote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HandoverController extends Controller
{
    /**
     * Determine shift name from the current hour.
     */
    private function resolveShift(Carbon $date): string
    {
        $hour = (int) $date->format('H');
        if ($hour >= 6 && $hour < 14)  return 'Morning Shift';
        if ($hour >= 14 && $hour < 22) return 'Afternoon Shift';
        return 'Night Shift';
    }

    public function index(Request $request)
    {
        $date = $request->date
            ? Carbon::parse($request->date)
            : now();

        // Latest log per activity for the selected day
        $handovers = ActivityLog::with(['activity', 'updatedBy'])
            ->whereDate('created_at', $date)
            ->latest()
            ->get()
            ->groupBy('activity_id')
            ->map(fn($group) => $group->first())
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
                    'person_staff_id'   => $person?->staff_id,
                    'person_department' => $person?->department,
                    'person_shift'      => $person?->shift,
                    'person_phone'      => $person?->phone,
                ];
            });

        // Sort: Pending first, then Done
        $handovers = $handovers->sortBy(fn($h) => $h->status === 'Pending' ? 0 : 1)->values();

        $stats = [
            'total'     => $handovers->count(),
            'completed' => $handovers->where('status', 'Done')->count(),
            'pending'   => $handovers->where('status', 'Pending')->count(),
        ];

        // Shift name based on current time (or noon for past dates)
        $shiftCheck = $date->isToday() ? now() : $date->copy()->setHour(12);
        $shiftName  = $this->resolveShift($shiftCheck);

        // Handover note for this date
        $handoverNote = HandoverNote::where('note_date', $date->toDateString())->first();

        return view('handover.index', compact(
            'date',
            'handovers',
            'stats',
            'shiftName',
            'handoverNote'
        ));
    }

    /**
     * Save or update the handover note for a given date.
     */
    public function saveNote(Request $request)
    {
        $request->validate([
            'note_date' => ['required', 'date'],
            'note'      => ['required', 'string', 'max:2000'],
        ]);

        HandoverNote::updateOrCreate(
            ['note_date' => $request->note_date],
            [
                'note'          => $request->note,
                'created_by_id' => session('staff_user_id'),
            ]
        );

        $queryString = $request->note_date !== now()->toDateString()
            ? '?date=' . $request->note_date
            : '';

        return redirect(route('handover.show') . $queryString)
            ->with('note_saved', 'Handover note saved successfully.');
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
            fputcsv($handle, ['Activity', 'Status', 'Remark', 'Updated By', 'Staff ID', 'Department', 'Shift', 'Updated At']);
            foreach ($logs as $log) {
                $person = $log->updatedBy;
                fputcsv($handle, [
                    $log->activity?->name ?? 'Unknown',
                    $log->status,
                    $log->remark ?? '-',
                    $person?->name ?? 'System',
                    $person?->staff_id ?? '-',
                    $person?->department ?? '-',
                    $person?->shift ?? '-',
                    $log->created_at->format('h:i A'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
