<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditLogController extends Controller
{
    /**
     * Display audit logs
     */
    public function index(Request $request)
    {
        // If audit_logs table doesn't exist, return an empty view instead of erroring
        try {
            $hasAuditTable = Schema::hasTable('audit_logs');
        } catch (\Exception $e) {
            $hasAuditTable = false;
        }

        if (!$hasAuditTable) {
            $logs = new LengthAwarePaginator([], 0, 50, 1, ['path' => request()->url()]);
            $users = collect();
            $events = collect();
            $models = collect();

            return view('admin.audit-logs.index', compact('logs', 'users', 'events', 'models'));
        }

        $query = AuditLog::with('user')->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by event
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by model type
        if ($request->filled('model')) {
            $query->where('auditable_type', 'like', '%' . $request->model . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                    ->orWhere('user_name', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->paginate(50);

        // Get filter options
        $users = User::select('id', 'name')->orderBy('name')->get();
        $events = AuditLog::select('event')
            ->distinct()
            ->orderBy('event')
            ->pluck('event');
        $models = AuditLog::select('auditable_type')
            ->distinct()
            ->whereNotNull('auditable_type')
            ->get()
            ->map(function ($log) {
                $parts = explode('\\', $log->auditable_type);
                return end($parts);
            })
            ->unique()
            ->sort()
            ->values();

        return view('admin.audit-logs.index', compact('logs', 'users', 'events', 'models'));
    }

    /**
     * Show detailed audit log
     */
    public function show(AuditLog $auditLog)
    {
        try {
            $hasAuditTable = Schema::hasTable('audit_logs');
        } catch (\Exception $e) {
            $hasAuditTable = false;
        }

        if (!$hasAuditTable) {
            abort(404);
        }

        $auditLog->load('user', 'auditable');

        return view('admin.audit-logs.show', compact('auditLog'));
    }


    /**
     * Get audit logs for a specific model
     */
    public function modelHistory(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
        ]);

        try {
            $hasAuditTable = Schema::hasTable('audit_logs');
        } catch (\Exception $e) {
            $hasAuditTable = false;
        }

        if (!$hasAuditTable) {
            $logs = new LengthAwarePaginator([], 0, 20, 1, ['path' => request()->url()]);
            return view('admin.audit-logs.model-history', compact('logs'));
        }

        $logs = AuditLog::where('auditable_type', $request->model_type)
            ->where('auditable_id', $request->model_id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.audit-logs.model-history', compact('logs'));
    }

    /**
     * Get user activity
     */
    public function userActivity(User $user)
    {
        try {
            $hasAuditTable = Schema::hasTable('audit_logs');
        } catch (\Exception $e) {
            $hasAuditTable = false;
        }

        if (!$hasAuditTable) {
            $logs = new LengthAwarePaginator([], 0, 50, 1, ['path' => request()->url()]);
            $stats = [
                'total_actions' => 0,
                'today_actions' => 0,
                'this_week_actions' => 0,
                'last_activity' => null,
            ];
            return view('admin.audit-logs.user-activity', compact('user', 'logs', 'stats'));
        }

        $logs = AuditLog::where('user_id', $user->id)
            ->with('auditable')
            ->latest()
            ->paginate(50);

        $stats = [
            'total_actions' => AuditLog::where('user_id', $user->id)->count(),
            'today_actions' => AuditLog::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
            'this_week_actions' => AuditLog::where('user_id', $user->id)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'last_activity' => AuditLog::where('user_id', $user->id)
                ->latest()
                ->first()?->created_at,
        ];

        return view('admin.audit-logs.user-activity', compact('user', 'logs', 'stats'));
    }

    /**
     * Get statistics
     */
    public function statistics(Request $request)
    {
        $period = $request->get('period', 'week'); // day, week, month, year

        $startDate = match ($period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfWeek(),
        };

        try {
            $hasAuditTable = Schema::hasTable('audit_logs');
        } catch (\Exception $e) {
            $hasAuditTable = false;
        }

        if (!$hasAuditTable) {
            $stats = [
                'total_logs' => 0,
                'unique_users' => 0,
                'by_event' => collect(),
                'by_user' => collect(),
                'by_model' => collect(),
                'recent_suspicious' => collect(),
            ];
            return view('admin.audit-logs.statistics', compact('stats', 'period'));
        }

        $stats = [
            'total_logs' => AuditLog::where('created_at', '>=', $startDate)->count(),
            'unique_users' => AuditLog::where('created_at', '>=', $startDate)
                ->distinct('user_id')
                ->count('user_id'),
            'by_event' => AuditLog::where('created_at', '>=', $startDate)
                ->select('event', DB::raw('count(*) as count'))
                ->groupBy('event')
                ->orderByDesc('count')
                ->get(),
            'by_user' => AuditLog::where('created_at', '>=', $startDate)
                ->select('user_id', 'user_name', DB::raw('count(*) as count'))
                ->groupBy('user_id', 'user_name')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
            'by_model' => AuditLog::where('created_at', '>=', $startDate)
                ->whereNotNull('auditable_type')
                ->select('auditable_type', DB::raw('count(*) as count'))
                ->groupBy('auditable_type')
                ->orderByDesc('count')
                ->get()
                ->map(function ($item) {
                    $parts = explode('\\', $item->auditable_type);
                    $item->model_name = end($parts);
                    return $item;
                }),
            'recent_suspicious' => AuditLog::where('created_at', '>=', $startDate)
                ->where('event', 'suspicious_activity')
                ->orWhere('event', 'login_failed')
                ->with('user')
                ->latest()
                ->limit(10)
                ->get(),
        ];

        return view('admin.audit-logs.statistics', compact('stats', 'period'));
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        try {
            $hasAuditTable = Schema::hasTable('audit_logs');
        } catch (\Exception $e) {
            $hasAuditTable = false;
        }

        if (!$hasAuditTable) {
            $logs = collect();
            $filename = 'audit_logs_' . now()->format('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($logs) {
                $file = fopen('php://output', 'w');
                fputcsv($file, [
                    'ID',
                    'Date/Time',
                    'User',
                    'Event',
                    'Model',
                    'Description',
                    'IP Address',
                    'URL',
                ]);
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $query = AuditLog::with('user')->latest();

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID',
                'Date/Time',
                'User',
                'Event',
                'Model',
                'Description',
                'IP Address',
                'URL',
            ]);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user_name,
                    $log->event_name,
                    $log->model_name ?? 'N/A',
                    $log->description,
                    $log->ip_address,
                    $log->url,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    /**
     * Clear old logs
     */
    public function clearOld(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30',
        ]);

        try {
            $hasAuditTable = Schema::hasTable('audit_logs');
        } catch (\Exception $e) {
            $hasAuditTable = false;
        }

        if (!$hasAuditTable) {
            return redirect()->back()->with('success', 'No audit logs table found; nothing to delete.');
        }

        $date = now()->subDays($request->days);
        $count = AuditLog::where('created_at', '<', $date)->delete();

        return redirect()->back()->with('success', "Deleted {$count} audit logs older than {$request->days} days.");
    }
}