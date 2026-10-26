<?php

namespace App\Providers;

use App\Contracts\AssistantResponseGenerator;
use App\Models\Appointment;
use App\Models\AppointmentScheduleDate;
use App\Models\AppointmentScheduleTemplate;
use App\Models\Client;
use App\Models\ClinicalRecord;
use App\Models\Pet;
use App\Models\Service;
use App\Models\User;
use App\Policies\AppointmentPolicy;
use App\Policies\AppointmentScheduleDatePolicy;
use App\Policies\AppointmentScheduleTemplatePolicy;
use App\Policies\ClientPolicy;
use App\Policies\ClinicalRecordPolicy;
use App\Policies\PetPolicy;
use App\Policies\ServicePolicy;
use App\Policies\UserPolicy;
use App\Services\GroqAssistantResponseGenerator;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AssistantResponseGenerator::class, GroqAssistantResponseGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiting();
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(ClinicalRecord::class, ClinicalRecordPolicy::class);
        Gate::policy(AppointmentScheduleDate::class, AppointmentScheduleDatePolicy::class);
        Gate::policy(AppointmentScheduleTemplate::class, AppointmentScheduleTemplatePolicy::class);
        Gate::policy(Appointment::class, AppointmentPolicy::class);
        Gate::policy(Pet::class, PetPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('simon', function (Request $request) {
            return Limit::perMinute(config('simon.rate_limit'))->by(
                ($request->user()?->getAuthIdentifier() ?? $request->ip()).'|'.$request->ip(),
            );
        });
    }
}
