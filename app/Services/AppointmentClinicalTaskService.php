<?php

namespace App\Services;

use App\Models\AppointmentClinicalTask;
use App\Models\ClinicalRecord;
use Illuminate\Support\Facades\DB;

class AppointmentClinicalTaskService
{
    public function resolveFrom(ClinicalRecord $record): void
    {
        if ($record->treatment_session_id === null) {
            return;
        }
        DB::transaction(function () use ($record): void {
            $task = AppointmentClinicalTask::query()->whereHas('appointment', fn ($query) => $query
                ->where('status', 'attended')->where('treatment_session_id', $record->treatment_session_id))
                ->whereNull('resolved_at')
                ->lockForUpdate()->first();
            if ($task && $record->treatmentSession()->where('status', 'completed')->exists()) {
                $task->forceFill(['clinical_record_id' => $record->id, 'resolved_at' => now()])->save();
            }
        });
    }
}
