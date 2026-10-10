<?php

namespace App\Services;

use App\Models\AppointmentClinicalTask;
use App\Models\ClinicalRecord;
use App\Models\TreatmentSession;
use Illuminate\Support\Facades\DB;

class AppointmentClinicalTaskService
{
    public function resolveFrom(ClinicalRecord $record): void
    {
        if ($record->treatment_session_id === null) {
            return;
        }

        DB::transaction(function () use ($record): void {
            $session = TreatmentSession::query()->lockForUpdate()->findOrFail($record->treatment_session_id);

            $this->resolve($session, $record);
        });
    }

    public function resolveForSession(TreatmentSession $session): void
    {
        DB::transaction(function () use ($session): void {
            $locked = TreatmentSession::query()->lockForUpdate()->findOrFail($session->id);
            $record = ClinicalRecord::query()
                ->where('treatment_session_id', $locked->id)
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            if ($record) {
                $this->resolve($locked, $record);
            }
        });
    }

    private function resolve(TreatmentSession $session, ClinicalRecord $record): void
    {
        if ($session->status !== 'completed') {
            return;
        }

        AppointmentClinicalTask::query()
            ->whereHas('appointment', fn ($query) => $query
                ->where('status', 'attended')
                ->where('treatment_session_id', $session->id))
            ->whereNull('resolved_at')
            ->lockForUpdate()
            ->get()
            ->each(fn (AppointmentClinicalTask $task) => $task->forceFill([
                'clinical_record_id' => $record->id,
                'resolved_at' => now(),
            ])->save());
    }
}
