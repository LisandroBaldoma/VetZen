<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class GoogleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_redirected_to_google(): void
    {
        Socialite::fake('google');

        $this->get(route('google.redirect'))
            ->assertRedirect('https://socialite.fake/google/authorize');
    }

    public function test_google_redirect_uses_stateful_oauth(): void
    {
        config([
            'services.google.client_id' => 'google-client-id',
            'services.google.client_secret' => 'google-client-secret',
            'services.google.redirect' => route('google.callback'),
        ]);

        $this->get(route('google.redirect'))
            ->assertRedirectContains('accounts.google.com/o/oauth2/auth')
            ->assertSessionHas('state');
    }

    public function test_google_callback_rejects_invalid_oauth_state(): void
    {
        config([
            'services.google.client_id' => 'google-client-id',
            'services.google.client_secret' => 'google-client-secret',
            'services.google.redirect' => route('google.callback'),
        ]);

        $this->get(route('google.callback', ['code' => 'invalid-code', 'state' => 'invalid-state']))
            ->assertRedirect(route('login'))
            ->assertSessionHas('googleError');

        $this->assertGuest();
    }

    public function test_new_google_user_is_sent_to_registration_completion(): void
    {
        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-123',
            'name' => 'Google User',
            'email' => 'GOOGLE@example.com',
            'email_verified' => true,
        ]));

        $this->get(route('google.callback'))
            ->assertRedirect(route('google.registration.create'))
            ->assertSessionHas('google.registration.id', 'google-123')
            ->assertSessionHas('google.registration.name', 'Google User')
            ->assertSessionHas('google.registration.email', 'google@example.com')
            ->assertSessionHas('google.registration.issued_at');

        $this->get(route('google.registration.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('auth/complete-google-registration')
                ->where('name', 'Google User')
                ->where('email', 'google@example.com'),
            );
    }

    public function test_new_google_user_can_complete_registration(): void
    {
        Event::fake([Registered::class]);

        $response = $this->withSession([
            'google.registration' => [
                'id' => 'google-123',
                'name' => 'Google User',
                'email' => 'google@example.com',
                'issued_at' => now()->timestamp,
            ],
        ])->post(route('google.registration.store'), [
            'name' => 'Injected Name',
            'email' => 'admin@example.com',
            'google_id' => 'injected-google-id',
            'role' => 'admin',
            'phone' => '+54 11 5555 5555',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
        $response->assertSessionMissing('google.registration');
        $this->assertAuthenticated();

        $user = User::where('google_id', 'google-123')->firstOrFail();

        $this->assertSame('Google User', $user->name);
        $this->assertSame('google@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertTrue($user->hasRole('client'));
        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'phone' => '+54 11 5555 5555',
        ]);
        Event::assertDispatched(Registered::class, fn (Registered $event) => $event->user->is($user));
    }

    public function test_google_registration_requires_phone_and_valid_password(): void
    {
        $response = $this->withSession([
            'google.registration' => [
                'id' => 'google-123',
                'name' => 'Google User',
                'email' => 'google@example.com',
                'issued_at' => now()->timestamp,
            ],
        ])->post(route('google.registration.store'), [
            'phone' => '',
            'password' => 'different',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['phone', 'password']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('clients', 0);
    }

    public function test_expired_google_registration_cannot_be_completed(): void
    {
        $response = $this->withSession([
            'google.registration' => [
                'id' => 'google-123',
                'name' => 'Google User',
                'email' => 'google@example.com',
                'issued_at' => now()->subMinutes(11)->timestamp,
            ],
        ])->post(route('google.registration.store'), [
            'phone' => '+54 11 5555 5555',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionMissing('google.registration');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_existing_email_is_not_linked_automatically(): void
    {
        $user = User::factory()->create(['email' => 'existing@example.com']);

        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-123',
            'email' => 'existing@example.com',
            'email_verified' => true,
        ]));

        $this->get(route('google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('googleError');

        $this->assertGuest();
        $this->assertNull($user->fresh()->google_id);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_unverified_google_email_cannot_start_registration(): void
    {
        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-123',
            'email' => 'unverified@example.com',
            'email_verified' => false,
        ]));

        $this->get(route('google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('googleError');

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_linked_google_user_can_log_in(): void
    {
        $user = User::factory()->create(['google_id' => 'google-123']);

        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-123',
            'email' => 'changed@example.com',
        ]));

        $this->get(route('google.callback'))
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    }

    public function test_linked_google_user_with_two_factor_is_challenged_before_login(): void
    {
        $user = User::factory()->withTwoFactor()->create(['google_id' => 'google-123']);

        Socialite::fake('google', SocialiteUser::fake(['id' => 'google-123']));

        $this->get(route('google.callback'))
            ->assertRedirect(route('two-factor.login'))
            ->assertSessionHas('login.id', $user->id);

        $this->assertGuest();
    }

    public function test_google_account_link_requires_authentication(): void
    {
        $this->get(route('google.link.redirect'))
            ->assertRedirect(route('login'));
    }

    public function test_google_account_link_requires_password_confirmation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('google.link.redirect'))
            ->assertRedirect(route('password.confirm'));

        $this->actingAs($user)
            ->get(route('google.link.callback'))
            ->assertRedirect(route('password.confirm'));
    }

    public function test_existing_admin_can_explicitly_link_same_google_email(): void
    {
        $admin = User::factory()->admin()->create(['email' => 'admin@example.com']);

        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-admin',
            'email' => 'ADMIN@example.com',
            'email_verified' => true,
        ]));

        $this->actingAs($admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('google.link.callback'))
            ->assertRedirect(route('security.edit'));

        $admin->refresh();

        $this->assertSame('google-admin', $admin->google_id);
        $this->assertTrue($admin->hasRole('admin'));
        $this->assertFalse($admin->hasRole('client'));
    }

    public function test_google_account_with_different_email_cannot_be_linked(): void
    {
        $user = User::factory()->create(['email' => 'client@example.com']);

        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-123',
            'email' => 'other@example.com',
            'email_verified' => true,
        ]));

        $this->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('google.link.callback'))
            ->assertRedirect(route('security.edit'));

        $this->assertNull($user->fresh()->google_id);
    }

    public function test_google_identity_cannot_be_linked_to_two_users(): void
    {
        User::factory()->create(['google_id' => 'google-123']);
        $otherUser = User::factory()->create(['email' => 'other@example.com']);

        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-123',
            'email' => 'other@example.com',
            'email_verified' => true,
        ]));

        $this->actingAs($otherUser)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('google.link.callback'))
            ->assertRedirect(route('security.edit'));

        $this->assertNull($otherUser->fresh()->google_id);
    }
}
