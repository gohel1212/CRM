<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::with(['activityable', 'assignee'])
            ->latest()
            ->paginate(10);

        return view('activities.index', compact('activities'));
    }

    /**
     * Show the calendar view of activities.
     */
    public function calendar()
    {
        $activities = Activity::with(['activityable', 'assignee'])
            ->where('due_date', '>=', now()->startOfMonth())
            ->where('due_date', '<=', now()->endOfMonth())
            ->get();

        return view('activities.calendar', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('activities.create', compact('users'));
    }

    /**
     * Store a newly created activity in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Activity creation started', $request->all());
            
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|string|in:meeting,call,task,email',
                'status' => 'required|string|in:pending,completed,cancelled',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'activityable_type' => 'required|string',
                'activityable_id' => 'required|integer'
            ]);

            Log::info('Validation passed', $validated);

            $activity = Activity::create([
                ...$validated,
                'user_id' => auth()->id(),
            ]);

            Log::info('Activity created', ['activity' => $activity->toArray()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Activity created successfully.',
                    'activity' => $activity
                ]);
            }

            return redirect()
                ->route('calendar', ['date' => $validated['start_date']])
                ->with('success', 'Activity created successfully.');
        } catch (\Exception $e) {
            Log::error('Activity creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create activity: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to create activity. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity->load(['activityable', 'assignee', 'creator']);
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $users = User::all();
        return view('activities.edit', compact('activity', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:meeting,call,task,email',
            'status' => 'required|string|in:pending,completed,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $activity->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Activity updated successfully.',
                'activity' => $activity
            ]);
        }

        return redirect()->route('activities.index')
            ->with('success', 'Activity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully.');
    }
}
