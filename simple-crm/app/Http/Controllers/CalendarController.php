<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Display the calendar view with activities.
     */
    public function index(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $view = $request->input('view', 'month'); // Default to month view

        switch ($view) {
            case 'day':
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                $previousDate = $date->copy()->subDay()->format('Y-m-d');
                $nextDate = $date->copy()->addDay()->format('Y-m-d');
                break;
            case 'week':
                $startDate = $date->copy()->startOfWeek();
                $endDate = $date->copy()->endOfWeek();
                $previousDate = $date->copy()->subWeek()->format('Y-m-d');
                $nextDate = $date->copy()->addWeek()->format('Y-m-d');
                break;
            case 'month':
            default:
                $startDate = $date->copy()->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                $previousDate = $date->copy()->subMonth()->format('Y-m-d');
                $nextDate = $date->copy()->addMonth()->format('Y-m-d');
                break;
        }

        $activities = Activity::query()
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderBy('start_date')
            ->get();

        $calendar = [
            'date' => $date,
            'view' => $view,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'previousDate' => $previousDate,
            'nextDate' => $nextDate,
            'startOfMonth' => $date->copy()->startOfMonth(),
            'endOfMonth' => $date->copy()->endOfMonth(),
            'daysInMonth' => $date->daysInMonth,
            'firstDayOfWeek' => $startDate->dayOfWeek,
        ];

        return view('calendar.index', compact('activities', 'calendar'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|string|in:meeting,call,task,email',
                'subject' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'start_time' => 'nullable|date_format:H:i',
                'status' => 'required|string|in:pending,completed,cancelled',
                'activityable_type' => 'required|string',
                'activityable_id' => 'required|integer',
            ]);

            $startDate = Carbon::parse($validated['start_date']);
            $endDate = $startDate->copy()->endOfDay();

            // Create the activity with all required fields
            $activity = Activity::create([
                'type' => $validated['type'],
                'subject' => $validated['subject'],
                'description' => $validated['description'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'due_date' => $endDate, // Set due_date same as end_date
                'status' => $validated['status'],
                'activityable_type' => $validated['activityable_type'],
                'activityable_id' => $validated['activityable_id'],
                'assigned_to' => Auth::id(),
                'created_by' => Auth::id(),
                'is_all_day' => empty($validated['start_time'])
            ]);

            // Get the month view URL for the activity's date
            $redirectUrl = route('calendar', [
                'date' => $startDate->format('Y-m-d'),
                'view' => 'month'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Activity created successfully',
                'activity' => $activity,
                'redirect_url' => $redirectUrl
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create activity', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create activity: ' . $e->getMessage()
            ], 422);
        }
    }
} 