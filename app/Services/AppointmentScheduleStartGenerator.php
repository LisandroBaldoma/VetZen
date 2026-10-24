<?php

namespace App\Services;

use Carbon\CarbonImmutable;

class AppointmentScheduleStartGenerator
{
    /** @param array<int, array{starts_at: string, ends_at: string}> $blocks
     * @return array<int, string>
     */
    public function generate(array $blocks, int $intervalMinutes): array
    {
        $starts = [];

        foreach ($blocks as $block) {
            $start = CarbonImmutable::createFromFormat('H:i', substr($block['starts_at'], 0, 5));
            $end = CarbonImmutable::createFromFormat('H:i', substr($block['ends_at'], 0, 5));

            while ($start->lt($end)) {
                $starts[] = $start->format('H:i');
                $start = $start->addMinutes($intervalMinutes);
            }
        }

        return $starts;
    }
}
