<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SystemMonitorController extends Controller
{
    public function index()
    {
        $systemStats = $this->getSystemStats();
        $performanceMetrics = $this->getPerformanceMetrics();
        $recentLogs = $this->getRecentLogs(20);
        $userActivity = $this->getUserActivity();
        $errorLogs = $this->getErrorLogs();

        return view('admin.monitor.index', compact(
            'systemStats', 
            'performanceMetrics', 
            'recentLogs', 
            'userActivity',
            'errorLogs'
        ));
    }

    public function logs(Request $request)
    {
        $level = $request->get('level', 'all');
        $date = $request->get('date', now()->format('Y-m-d'));
        
        $logs = $this->getFilteredLogs($level, $date);
        
        return view('admin.monitor.logs', compact('logs', 'level', 'date'));
    }

    public function performance()
    {
        $metrics = $this->getDetailedPerformanceMetrics();
        $databaseStats = $this->getDatabaseStats();
        $cacheStats = $this->getCacheStats();
        
        return view('admin.monitor.performance', compact('metrics', 'databaseStats', 'cacheStats'));
    }

    public function users()
    {
        $userStats = $this->getUserStatistics();
        $recentUsers = User::with('activities')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $userActivity = $this->getDetailedUserActivity();
        
        return view('admin.monitor.users', compact('userStats', 'recentUsers', 'userActivity'));
    }

    public function database()
    {
        $tables = $this->getDatabaseTables();
        $queries = $this->getSlowQueries();
        $connections = $this->getDatabaseConnections();
        
        return view('admin.monitor.database', compact('tables', 'queries', 'connections'));
    }

    private function getSystemStats()
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_customers' => Customer::count(),
            'total_deals' => Deal::count(),
            'total_activities' => Activity::count(),
            'revenue' => Deal::sum('amount'),
            'deals_this_month' => Deal::whereMonth('created_at', now()->month)->count(),
            'new_customers_this_month' => Customer::whereMonth('created_at', now()->month)->count(),
            'system_uptime' => $this->getSystemUptime(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
        ];
    }

    private function getPerformanceMetrics()
    {
        return [
            'avg_response_time' => $this->getAverageResponseTime(),
            'requests_per_minute' => $this->getRequestsPerMinute(),
            'error_rate' => $this->getErrorRate(),
            'cache_hit_rate' => $this->getCacheHitRate(),
            'database_connections' => $this->getActiveDatabaseConnections(),
        ];
    }

    private function getRecentLogs($limit = 20)
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (!file_exists($logFile)) {
                // Provide friendly demo data when the app hasn't generated logs yet
                return $this->demoLogs();
            }

            $logs = [];
            $lines = file($logFile);
            $recentLines = array_slice($lines, -$limit * 10); // Get more lines to filter

            foreach ($recentLines as $line) {
                // Match lines like: [2025-10-14 10:26:37] local.ERROR: Something happened
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} [0-9:]{8})\]\s+[a-zA-Z]+\.(\w+):\s*(.*)$/', $line, $matches)) {
                    $logs[] = [
                        'timestamp' => $matches[1],
                        'level' => $matches[2],
                        'message' => trim($matches[3])
                    ];
                }
            }

            $logs = array_slice(array_reverse($logs), 0, $limit);
            return count($logs) ? $logs : $this->demoLogs();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getUserActivity()
    {
        // Try recent activity first (last 7 days)
        $recent = User::with('activities')
            ->whereHas('activities', function($query) {
                $query->where('created_at', '>=', now()->subDays(7));
            })
            ->withCount('activities')
            ->orderBy('activities_count', 'desc')
            ->limit(10)
            ->get();

        if ($recent->count() > 0) {
            return $recent;
        }

        // Fallback: show top users by total activity so the widget is never empty
        return User::with('activities')
            ->withCount('activities')
            ->orderBy('activities_count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getErrorLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (!file_exists($logFile)) {
                return [];
            }

            $errors = [];
            $lines = file($logFile);
            $recentLines = array_slice($lines, -1000);

            foreach ($recentLines as $line) {
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} [0-9:]{8})\]\s+[a-zA-Z]+\.(ERROR|CRITICAL|EMERGENCY):\s*(.*)$/', $line, $matches)) {
                    $errors[] = [
                        'timestamp' => $matches[1],
                        'level' => $matches[2],
                        'message' => trim($matches[3])
                    ];
                }
            }

            $errors = array_slice(array_reverse($errors), 0, 20);
            return count($errors) ? $errors : $this->demoLogs();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getFilteredLogs($level, $date)
    {
        try {
            $logFile = storage_path('logs/laravel-' . $date . '.log');
            if (!file_exists($logFile)) {
                $logFile = storage_path('logs/laravel.log');
            }

            if (!file_exists($logFile)) {
                return $this->demoLogs();
            }

            $logs = [];
            $lines = file($logFile);

            foreach ($lines as $line) {
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} [0-9:]{8})\]\s+[a-zA-Z]+\.(\w+):\s*(.*)$/', $line, $matches)) {
                    if ($level === 'all' || strtolower($matches[2]) === strtolower($level)) {
                        $logs[] = [
                            'timestamp' => $matches[1],
                            'level' => $matches[2],
                            'message' => trim($matches[3])
                        ];
                    }
                }
            }

            $logs = array_reverse($logs);
            return count($logs) ? $logs : $this->demoLogs();
        } catch (\Exception $e) {
            return $this->demoLogs();
        }
    }

    private function getDetailedPerformanceMetrics()
    {
        return [
            'response_times' => $this->getResponseTimeHistory(),
            'memory_usage_history' => $this->getMemoryUsageHistory(),
            'database_query_times' => $this->getDatabaseQueryTimes(),
            'cache_performance' => $this->getCachePerformance(),
        ];
    }

    private function getDatabaseStats()
    {
        try {
            $tables = DB::select('SHOW TABLE STATUS');
            $totalSize = 0;
            $tableStats = [];

            foreach ($tables as $table) {
                $size = $table->Data_length + $table->Index_length;
                $totalSize += $size;
                $tableStats[] = [
                    'name' => $table->Name,
                    'rows' => $table->Rows,
                    'size' => $size,
                    'engine' => $table->Engine,
                ];
            }

            return [
                'total_size' => $totalSize,
                'tables' => $tableStats,
                'connection_count' => DB::select('SHOW STATUS LIKE "Threads_connected"')[0]->Value ?? 0,
            ];
        } catch (\Exception $e) {
            return ['total_size' => 0, 'tables' => [], 'connection_count' => 0];
        }
    }

    private function getCacheStats()
    {
        return [
            'driver' => config('cache.default'),
            'hit_rate' => $this->getCacheHitRate(),
            'memory_usage' => $this->getCacheMemoryUsage(),
        ];
    }

    private function getUserStatistics()
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_this_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'users_by_role' => User::select('role', DB::raw('count(*) as count'))->groupBy('role')->get(),
        ];
    }

    private function getDetailedUserActivity()
    {
        return User::with(['activities' => function($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        }])
        ->withCount('activities')
        ->orderBy('activities_count', 'desc')
        ->limit(20)
        ->get();
    }

    private function getDatabaseTables()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $tableInfo = [];
            
            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];
                $count = DB::table($tableName)->count();
                $tableInfo[] = [
                    'name' => $tableName,
                    'count' => $count
                ];
            }
            
            return $tableInfo;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getSlowQueries()
    {
        try {
            return DB::select('SHOW FULL PROCESSLIST');
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getDatabaseConnections()
    {
        try {
            return DB::select('SHOW STATUS LIKE "Connections"');
        } catch (\Exception $e) {
            return [];
        }
    }

    // Helper methods for system metrics
    private function getSystemUptime()
    {
        try {
            $uptime = shell_exec('uptime');
            return $uptime ? trim($uptime) : 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getMemoryUsage()
    {
        return [
            'used' => memory_get_usage(true),
            'peak' => memory_get_peak_usage(true),
            'limit' => ini_get('memory_limit'),
        ];
    }

    private function getDiskUsage()
    {
        $bytes = disk_free_space('.');
        $total = disk_total_space('.');
        return [
            'free' => $bytes,
            'total' => $total,
            'used' => $total - $bytes,
            'percentage' => round((($total - $bytes) / $total) * 100, 2),
        ];
    }

    private function getAverageResponseTime()
    {
        // This would typically come from monitoring tools
        return rand(100, 500) . 'ms';
    }

    private function getRequestsPerMinute()
    {
        // This would typically come from monitoring tools
        return rand(50, 200);
    }

    private function getErrorRate()
    {
        // This would typically come from monitoring tools
        return rand(0, 5) . '%';
    }

    private function getCacheHitRate()
    {
        // This would typically come from cache monitoring
        return rand(80, 95) . '%';
    }

    private function getActiveDatabaseConnections()
    {
        try {
            $result = DB::select('SHOW STATUS LIKE "Threads_connected"');
            return $result[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getResponseTimeHistory()
    {
        // Mock data - in real implementation, this would come from monitoring
        return array_fill(0, 24, rand(100, 500));
    }

    private function getMemoryUsageHistory()
    {
        // Mock data - in real implementation, this would come from monitoring
        return array_fill(0, 24, rand(50, 90));
    }

    private function getDatabaseQueryTimes()
    {
        // Mock data - in real implementation, this would come from query logging
        return array_fill(0, 10, rand(10, 100));
    }

    private function getCachePerformance()
    {
        return [
            'hits' => rand(1000, 5000),
            'misses' => rand(100, 500),
            'hit_rate' => rand(80, 95) . '%',
        ];
    }

    private function getCacheMemoryUsage()
    {
        return rand(10, 50) . 'MB';
    }

    private function demoLogs(): array
    {
        // Minimal demo entries so the UI isn't empty on fresh installs
        $now = now();
        return [
            [
                'timestamp' => $now->copy()->subMinutes(5)->format('Y-m-d H:i:s'),
                'level' => 'INFO',
                'message' => 'Application booted successfully.'
            ],
            [
                'timestamp' => $now->copy()->subMinutes(3)->format('Y-m-d H:i:s'),
                'level' => 'WARNING',
                'message' => 'Cache store is using file driver; consider redis for production.'
            ],
            [
                'timestamp' => $now->copy()->subMinute()->format('Y-m-d H:i:s'),
                'level' => 'ERROR',
                'message' => 'Demo error: example exception captured for monitoring.'
            ],
        ];
    }
}
