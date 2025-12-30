<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts for different entities
        $counts = [
            'customers' => Customer::count(),
            'contacts' => Contact::count(),
            'deals' => Deal::count(),
            'activities' => Activity::count(),
        ];

        // Get recent activities (last 14 days) regardless of status
        $recentActivities = Activity::with(['activityable'])
            ->orderByDesc('start_date')
            ->where('start_date', '>=', now()->subDays(14))
            ->take(5)
            ->get();

        // Get deals in progress
        $activeDeals = Deal::where('status', 'in_progress')
            ->latest()
            ->take(5)
            ->get();

        // Get upcoming activities (future pending)
        $upcomingActivities = Activity::where('status', 'pending')
            ->whereDate('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();

        return view('dashboard', compact('counts', 'recentActivities', 'activeDeals', 'upcomingActivities'));
    }

    public function apiIndex()
    {
        // Get counts for different entities
        $counts = [
            'customers' => Customer::count(),
            'contacts' => Contact::count(),
            'deals' => Deal::count(),
            'activities' => Activity::count(),
        ];

        // Get recent activities (last 14 days) regardless of status
        $recentActivities = Activity::with(['activityable'])
            ->orderByDesc('start_date')
            ->where('start_date', '>=', now()->subDays(14))
            ->take(5)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'subject' => $activity->subject,
                    'status' => $activity->status,
                    'start_date' => $activity->start_date?->format('M d, Y'),
                    'type' => $activity->type,
                ];
            });

        // Get upcoming activities (future pending)
        $upcomingActivities = Activity::where('status', 'pending')
            ->whereDate('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'subject' => $activity->subject,
                    'type' => $activity->type,
                    'start_date' => $activity->start_date?->format('M d, Y h:i A'),
                ];
            });

        return response()->json([
            'counts' => $counts,
            'recent_activities' => $recentActivities,
            'upcoming_activities' => $upcomingActivities,
        ]);
    }
}