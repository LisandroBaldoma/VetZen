<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\Treatment;
use App\Models\User;
use App\Notifications\TreatmentNotification;
use App\Services\TreatmentNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationInboxTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_and_mark_only_their_notifications_as_read(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $notification = $this->notificationFor($user);
        $otherNotification = $this->notificationFor($otherUser);

        $this->actingAs($user)->get(route('notifications.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('notifications/index')->has('notifications.data', 1));

        $this->actingAs($user)->patch(route('notifications.read', $notification->id))
            ->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);

        $this->actingAs($user)->patch(route('notifications.read', $otherNotification->id))
            ->assertNotFound();
        $this->assertNull($otherNotification->fresh()->read_at);
    }

    public function test_user_can_mark_all_of_their_notifications_as_read(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->notificationFor($user);
        $otherNotification = $this->notificationFor($otherUser);

        $this->actingAs($user)->patch(route('notifications.read-all'))->assertRedirect();

        $this->assertSame(0, $user->fresh()->unreadNotifications()->count());
        $this->assertSame(1, $otherUser->unreadNotifications()->count());
        $this->assertNull($otherNotification->fresh()->read_at);
    }

    public function test_treatment_updates_are_sent_only_to_the_pet_owner(): void
    {
        Notification::fake();
        $pet = Pet::factory()->create();
        $catalogTreatment = Treatment::factory()->create();
        $treatment = $pet->treatments()->make([
            'treatment_name' => 'Fisioterapia',
            'treatment_description' => 'Tratamiento de prueba',
            'planned_sessions' => 1,
            'default_session_price' => '0.00',
            'currency' => 'ARS',
            'starts_on' => now()->toDateString(),
            'status' => 'pending',
        ]);
        $treatment->treatment()->associate($catalogTreatment);
        $treatment->save();
        $treatment->load('pet.client.user');

        app(TreatmentNotificationService::class)->send($treatment, 'asignado');

        Notification::assertSentTo($treatment->pet->client->user, TreatmentNotification::class, function (TreatmentNotification $notification): bool {
            return $notification->toArray(new \stdClass)['event'] === 'asignado';
        });
    }

    private function notificationFor(User $user): object
    {
        return $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\AppointmentNotification',
            'data' => ['event' => 'confirmación', 'pet_name' => 'Luna', 'service_name' => 'Consulta'],
        ]);
    }
}
