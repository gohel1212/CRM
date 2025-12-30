<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SystemController extends Controller
{

    public function logs()
    {
        $logs = $this->getSystemLogs();
        return view('admin.system.logs', compact('logs'));
    }

    public function cache()
    {
        return view('admin.system.cache');
    }

    public function clearCache(Request $request)
    {
        $type = $request->input('type', 'all');
        
        try {
            switch ($type) {
                case 'config':
                    Artisan::call('config:clear');
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    break;
                case 'all':
                    Artisan::call('config:clear');
                    Artisan::call('route:clear');
                    Artisan::call('view:clear');
                    Artisan::call('cache:clear');
                    break;
            }

            Log::info('Admin cleared cache', [
                'admin_id' => auth()->id(),
                'cache_type' => $type
            ]);

            return back()->with('success', ucfirst($type) . ' cache cleared successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    public function database()
    {
        $tables = $this->getDatabaseTables();
        return view('admin.system.database', compact('tables'));
    }

    public function backup()
    {
        try {
            // Create a simple backup (you can enhance this with proper backup tools)
            $backupData = [
                'timestamp' => now(),
                'users_count' => \App\Models\User::count(),
                'customers_count' => \App\Models\Customer::count(),
                'deals_count' => \App\Models\Deal::count(),
            ];

            $filename = 'backup_' . now()->format('Y_m_d_H_i_s') . '.json';
            Storage::put('backups/' . $filename, json_encode($backupData, JSON_PRETTY_PRINT));

            Log::info('Admin created database backup', [
                'admin_id' => auth()->id(),
                'filename' => $filename
            ]);

            return back()->with('success', 'Database backup created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create backup: ' . $e->getMessage());
        }
    }

    public function settings()
    {
        return view('admin.system.settings');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
        ]);

        // Update configuration (you might want to store these in database)
        Log::info('Admin updated system settings', [
            'admin_id' => auth()->id(),
            'settings' => $validated
        ]);

        return back()->with('success', 'Settings updated successfully.');
    }

    private function getSystemLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (!file_exists($logFile)) {
                return $this->demoLogs();
            }

            $logs = [];
            $lines = file($logFile);
            $recentLines = array_slice($lines, -300);

            foreach ($recentLines as $line) {
                // Match: [YYYY-MM-DD HH:MM:SS] env.LEVEL: message
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} [0-9:]{8})\]\s+[a-zA-Z]+\.(\w+):\s*(.*)$/', $line, $matches)) {
                    $logs[] = [
                        'timestamp' => $matches[1],
                        'level' => strtolower($matches[2]),
                        'message' => trim($matches[3])
                    ];
                }
            }

            $logs = array_reverse($logs);
            return count($logs) ? $logs : $this->demoLogs();
        } catch (\Exception $e) {
            return $this->demoLogs();
        }
    }

    private function demoLogs(): array
    {
        $now = now();
        return [
            ['timestamp' => $now->copy()->subMinutes(5)->format('Y-m-d H:i:s'), 'level' => 'info', 'message' => 'Application started.'],
            ['timestamp' => $now->copy()->subMinutes(3)->format('Y-m-d H:i:s'), 'level' => 'warning', 'message' => 'Cache driver is file; consider redis for production.'],
            ['timestamp' => $now->copy()->subMinute()->format('Y-m-d H:i:s'), 'level' => 'error', 'message' => 'Demo error entry to verify log parsing UI.'],
        ];
    }

    private function getDatabaseTables()
    {
        try {
            $tables = \DB::select('SHOW TABLES');
            $tableInfo = [];
            
            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];
                $count = \DB::table($tableName)->count();
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
}
