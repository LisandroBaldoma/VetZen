<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_audit_logs_and_client_cannot(): void
    {
        $admin = User::factory()->admin()->create();
        $client = User::factory()->create();
        app(AuditLogger::class)->record($admin, 'pet_treatment.assigned', $client, null, ['status' => 'pending']);

        $this->actingAs($admin)->get(route('admin.audit-logs.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('admin/audit-logs/index')->has('logs.data', 1));

        $this->actingAs($client)->get(route('admin.audit-logs.index'))->assertForbidden();
    }

    public function test_audit_log_rolls_back_with_its_transaction(): void
    {
        $user = User::factory()->create();

        try {
            DB::transaction(function () use ($user): void {
                app(AuditLogger::class)->record($user, 'appointment.status_changed', $user, ['status' => 'pending'], ['status' => 'cancelled']);
                throw new RuntimeException('Rollback');
            });
        } catch (RuntimeException) {
        }

        $this->assertDatabaseCount('audit_logs', 0);
    }

    public function test_audit_log_keeps_actor_resource_and_changed_values(): void
    {
        $actor = User::factory()->create();
        $subject = User::factory()->create();
        app(AuditLogger::class)->record($actor, 'appointment.status_changed', $subject, ['status' => 'pending'], ['status' => 'approved']);

        $log = AuditLog::query()->sole();
        $this->assertTrue($log->user->is($actor));
        $this->assertTrue($log->auditable->is($subject));
        $this->assertSame(['status' => 'pending'], $log->old_values);
        $this->assertSame(['status' => 'approved'], $log->new_values);
    }
}
