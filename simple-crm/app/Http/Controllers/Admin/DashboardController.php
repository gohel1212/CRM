<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Activity;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{

    public function index()
    {
        // System Statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_customers' => Customer::count(),
            'total_deals' => Deal::count(),
            'total_activities' => Activity::count(),
            'revenue' => Deal::sum('amount'),
            'deals_this_month' => Deal::whereMonth('created_at', now()->month)->count(),
            'new_customers_this_month' => Customer::whereMonth('created_at', now()->month)->count(),
        ];

        // Audit Log Statistics - guard against missing table (safe check)
        try {
            $hasAuditTable = Schema::hasTable('audit_logs');
        } catch (\Exception $e) {
            $hasAuditTable = false;
        }

        if ($hasAuditTable) {
            $audit_stats = [
                'total_logs' => AuditLog::count(),
                'logs_today' => AuditLog::whereDate('created_at', today())->count(),
                'logs_this_week' => AuditLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'failed_logins' => AuditLog::where('event', 'failed_login')->whereDate('created_at', today())->count(),
            ];
        } else {
            $audit_stats = [
                'total_logs' => 0,
                'logs_today' => 0,
                'logs_this_week' => 0,
                'failed_logins' => 0,
            ];
        }

        // User Statistics
        $user_stats = [
            'active_users' => User::where('is_active', true)->count(),
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
        ];

        // Revenue Statistics
        $revenue_stats = [
            'total_revenue' => Deal::sum('amount') ?? 0,
            'monthly_revenue' => Deal::whereMonth('created_at', now()->month)->sum('amount') ?? 0,
        ];

        // Deal Statistics
        $deal_stats = [
            'total_deals' => Deal::count(),
            'active_deals' => Deal::where('status', 'active')->count(),
            'closed_deals' => Deal::where('status', 'closed')->count(),
        ];

        // Recent Logs for Dashboard
        if ($hasAuditTable) {
            $recent_logs = AuditLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            $recent_logs = collect();
        }

        // Audit Event Distribution (Today) and Most Active Users (by audit logs)
        if ($hasAuditTable) {
            $audit_events = AuditLog::select('event', DB::raw('count(*) as count'))
                ->whereDate('created_at', today())
                ->groupBy('event')
                ->orderBy('count', 'desc')
                ->get();

            $active_users = AuditLog::select('user_id', 'user_name', DB::raw('count(*) as action_count'))
                ->whereDate('created_at', today())
                ->whereNotNull('user_id')
                ->groupBy('user_id', 'user_name')
                ->orderBy('action_count', 'desc')
                ->limit(5)
                ->get();
        } else {
            $audit_events = collect();
            $active_users = collect();
        }

        // Recent Activities
        $recent_activities = Activity::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top Customers by Deal Value
        $top_customers = Customer::withSum('deals', 'amount')
            ->orderBy('deals_sum_amount', 'desc')
            ->limit(5)
            ->get();

        // Deal Status Distribution
        $deal_statuses = Deal::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Monthly Revenue Chart Data
        $monthly_revenue = Deal::select(
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('SUM(amount) as revenue')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'audit_stats',
            'user_stats',
            'revenue_stats',
            'deal_stats',
            'recent_logs',
            'audit_events',
            'active_users',
            'recent_activities',
            'top_customers',
            'deal_statuses',
            'monthly_revenue'
        ));
    }
}
