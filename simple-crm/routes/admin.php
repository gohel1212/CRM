<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\SystemMonitorController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

// Admin authentication routes (no auth middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin protected routes (require auth)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User management
    Route::resource('users', UserManagementController::class);
    Route::get('/users-advanced', [UserManagementController::class, 'advanced'])->name('users.advanced');
    Route::get('/users-export', [UserManagementController::class, 'export'])->name('users.export');
    Route::post('/users-bulk', [UserManagementController::class, 'bulk'])->name('users.bulk');
    Route::get('/users/{user}/details', [UserManagementController::class, 'details'])->name('users.details');
    
    // Audit logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
    Route::get('/audit-logs-statistics', [AuditLogController::class, 'statistics'])->name('audit-logs.statistics');
    Route::get('/audit-logs-user-activity/{user}', [AuditLogController::class, 'userActivity'])->name('audit-logs.user-activity');
    Route::get('/audit-logs-export', [AuditLogController::class, 'export'])->name('audit-logs.export');
    
    // System management
    Route::get('/system', [SystemController::class, 'index'])->name('system.index');
    Route::get('/system/logs', [SystemController::class, 'logs'])->name('system.logs');
    Route::get('/system/cache', [SystemController::class, 'cache'])->name('system.cache');
    // Cache actions
    Route::post('/system/cache/clear', [SystemController::class, 'clearCache'])->name('system.cache.clear');

    Route::get('/system/database', [SystemController::class, 'database'])->name('system.database');
    // Database backup
    Route::post('/system/backup', [SystemController::class, 'backup'])->name('system.backup');

    Route::get('/system/settings', [SystemController::class, 'settings'])->name('system.settings');
    // Settings update
    Route::post('/system/settings', [SystemController::class, 'updateSettings'])->name('system.settings.update');
    
    // System monitoring
    Route::get('/monitor', [SystemMonitorController::class, 'index'])->name('monitor.index');
    Route::get('/monitor/users', [SystemMonitorController::class, 'users'])->name('monitor.users');
    Route::get('/monitor/performance', [SystemMonitorController::class, 'performance'])->name('monitor.performance');
    Route::get('/monitor/database', [SystemMonitorController::class, 'database'])->name('monitor.database');
    Route::get('/monitor/logs', [SystemMonitorController::class, 'logs'])->name('monitor.logs');
    
    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/permissions/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::post('/permissions/bulk', [PermissionController::class, 'bulkUpdate'])->name('permissions.bulk');
});
