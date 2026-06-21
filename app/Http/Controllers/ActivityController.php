<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * List all activities.
     */
    public function index()
    {
        $activities = Activity::with(['assignedTo', 'lastUpdatedBy'])
            ->latest()
            ->get();

        $total     = $activities->count();
        $completed = $activities->where('status', 'Done')->count();
        $pending   = $activities->where('status', 'Pending')->count();

        return view('activities.index', compact('activities', 'total', 'completed', 'pending'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        return view('activities.create');
    }

    /**
     * Store a new activity.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $userId = session('staff_user_id');

        Activity::create([
            'name'               => $request->name,
            'description'        => $request->description,
            'status'             => 'Pending',
            'created_by_id'      => $userId,
            'last_updated_by_id' => $userId,
        ]);

        return redirect()->route('activities.index')
            ->with('success', 'Activity created successfully.');
    }

    /**
     * Show the update form for a single activity.
     */
    public function update(string $id)
    {
        $activity = Activity::with(['assignedTo', 'lastUpdatedBy'])->findOrFail($id);

        return view('activities.update', compact('activity', 'id'));
    }

    /**
     * Save a status update (creates an ActivityLog entry).
     */
    public function saveUpdate(Request $request, string $id)
    {
        $request->validate([
            'status' => ['required', 'in:Pending,Done'],
            'remark' => ['required', 'string', 'max:1000'],
        ]);

        $activity = Activity::findOrFail($id);
        $userId   = session('staff_user_id');

        // Update the activity itself
        $activity->update([
            'status'             => $request->status,
            'last_updated_by_id' => $userId,
        ]);

        // Log the update
        ActivityLog::create([
            'activity_id'   => $activity->id,
            'status'        => $request->status,
            'remark'        => $request->remark,
            'updated_by_id' => $userId,
        ]);

        return redirect()->route('activities.index')
            ->with('success', 'Activity updated successfully.');
    }

    /**
     * Delete an activity.
     */
    public function destroy(string $id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully.');
    }
}
