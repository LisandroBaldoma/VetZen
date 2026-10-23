<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearOperationalData extends Command
{
    protected $signature = 'vetzen:clear-operational-data {--force : Skip the confirmation prompt}';

    protected $description = 'Clear clinical, catalog, treatment, request, schedule, appointment, notification, and queue data.';

    /** @var array<int, string> */
    private const array TABLES = [
        'notifications',
        'appointment_reminder_deliveries',
        'appointment_link_histories',
        'appointment_clinical_tasks',
        'appointment_status_histories',
        'appointment_reschedule_histories',
        'appointments',
        'clinical_record_audits',
        'clinical_records',
        'service_requests',
        'treatment_sessions',
        'pet_treatment_procedures',
        'pet_treatments',
        'procedure_treatment',
        'appointment_schedule_date_service',
        'appointment_schedule_starts',
        'appointment_schedule_blocks',
        'appointment_schedule_dates',
        'appointment_schedule_template_service',
        'appointment_schedule_template_blocks',
        'appointment_schedule_templates',
        'procedures',
        'treatments',
        'services',
        'jobs',
        'job_batches',
        'failed_jobs',
    ];

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('This permanently deletes operational data. Continue?')) {
            $this->warn('No data was deleted.');

            return self::FAILURE;
        }

        DB::transaction(function (): void {
            foreach (self::TABLES as $table) {
                DB::table($table)->delete();
            }
        });

        $this->info('Operational data cleared. Users, clients, pets, authentication data, roles, and the Simón Knowledge Base were preserved.');

        return self::SUCCESS;
    }
}
