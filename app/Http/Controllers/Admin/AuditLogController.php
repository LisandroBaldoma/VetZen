<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    public function index(Request $request): Response
    {
        $action = $request->string('action')->toString();

        return Inertia::render('admin/audit-logs/index', [
            'logs' => AuditLog::query()
                ->with('user:id,name')
                ->when($action, fn ($query) => $query->where('action', $action))
                ->latest('id')
                ->paginate(30)
                ->withQueryString()
                ->through(fn (AuditLog $log): array => [
                    'id' => $log->id,
                    'action' => $log->action,
                    'resource' => class_basename($log->auditable_type).' #'.$log->auditable_id,
                    'user' => $log->user?->name ?? __('Sistema'),
                    'old_values' => $log->old_values,
                    'new_values' => $log->new_values,
                    'created_at' => $log->created_at->toISOString(),
                ]),
            'filters' => ['action' => $action],
            'actions' => AuditLog::query()->distinct()->orderBy('action')->pluck('action'),
        ]);
    }
}
