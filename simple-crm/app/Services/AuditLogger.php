<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AuditLogger
{
    /**
     * Log a custom event
     */
    public static function log($event, $description, $data = [])
    {
        $user = Auth::user();

        // If the audit_logs table does not exist or DB is not available,
        // fall back to writing the audit to the application log to avoid
        // throwing exceptions that break user flows (e.g. login).
        try {
            if (Schema::hasTable('audit_logs')) {
                return AuditLog::create([
                    'user_id' => $user?->id,
                    'user_name' => $user?->name ?? 'System',
                    'event' => $event,
                    'auditable_type' => $data['model_type'] ?? null,
                    'auditable_id' => $data['model_id'] ?? null,
                    'description' => $description,
                    'old_values' => $data['old_values'] ?? null,
                    'new_values' => $data['new_values'] ?? null,
                    'properties' => $data['properties'] ?? null,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                ]);
            }
        } catch (\Exception $e) {
            // ignore - we'll log the audit entry to the application log below
        }

        // Fallback: write the audit details to the application log instead
        try {
            Log::info('AuditLog (fallback): ' . $event, [
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'System',
                'description' => $description,
                'data' => $data,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
            ]);
        } catch (\Exception $e) {
            // If logging also fails, swallow exception to avoid breaking the app.
        }

        return null;
    }

    /**
     * Log user login
     */
    public static function logLogin($user)
    {
        return self::log('login', "User '{$user->name}' logged in", [
            'properties' => [
                'user_id' => $user->id,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Log user logout
     */
    public static function logLogout($user)
    {
        return self::log('logout', "User '{$user->name}' logged out", [
            'properties' => [
                'user_id' => $user->id,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Log failed login attempt
     */
    public static function logFailedLogin($email)
    {
        return self::log('failed_login', "Failed login attempt for '{$email}'", [
            'properties' => [
                'email' => $email,
            ],
        ]);
    }

    /**
     * Log data export
     */
    public static function logExport($modelType, $description)
    {
        return self::log('exported', $description, [
            'model_type' => $modelType,
        ]);
    }

    /**
     * Log data import
     */
    public static function logImport($modelType, $description, $count = null)
    {
        return self::log('imported', $description, [
            'model_type' => $modelType,
            'properties' => [
                'count' => $count,
            ],
        ]);
    }

    /**
     * Log bulk action
     */
    public static function logBulkAction($action, $modelType, $count, $description = null)
    {
        $description = $description ?? "Bulk {$action} performed on {$count} " . class_basename($modelType) . "(s)";

        return self::log('bulk_' . $action, $description, [
            'model_type' => $modelType,
            'properties' => [
                'action' => $action,
                'count' => $count,
            ],
        ]);
    }

    /**
     * Log permission change
     */
    public static function logPermissionChange($user, $oldPermissions, $newPermissions)
    {
        return self::log('permission_changed', "Permissions updated for user '{$user->name}'", [
            'model_type' => get_class($user),
            'model_id' => $user->id,
            'old_values' => ['permissions' => $oldPermissions],
            'new_values' => ['permissions' => $newPermissions],
        ]);
    }

    /**
     * Log role change
     */
    public static function logRoleChange($user, $oldRole, $newRole)
    {
        return self::log('role_changed', "Role changed for user '{$user->name}' from '{$oldRole}' to '{$newRole}'", [
            'model_type' => get_class($user),
            'model_id' => $user->id,
            'old_values' => ['role' => $oldRole],
            'new_values' => ['role' => $newRole],
        ]);
    }

    /**
     * Log suspicious activity
     */
    public static function logSuspiciousActivity($description, $data = [])
    {
        return self::log('suspicious_activity', $description, array_merge($data, [
            'properties' => array_merge($data['properties'] ?? [], [
                'flagged' => true,
            ]),
        ]));
    }
}