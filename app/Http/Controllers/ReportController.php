<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display activity log history with optional date-range and keyword filtering.
     */
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : null;

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : null;

        $search = trim($request->input('search', ''));

        $query = ActivityLog::with(['activity', 'updatedBy'])->latest();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                  ->orWhere('remark', 'like', "%{$search}%")
                  ->orWhereHas('activity', fn($a) => $a->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('updatedBy', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $reports      = $query->get();
        $totalCount   = $reports->count();
        $doneCount    = $reports->where('status', 'Done')->count();
        $pendingCount = $reports->where('status', 'Pending')->count();

        return view('reports.index', compact(
            'reports',
            'totalCount',
            'doneCount',
            'pendingCount',
            'startDate',
            'endDate',
            'search'
        ));
    }
}
