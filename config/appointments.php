<?php

return [
    'timezone' => 'America/Argentina/Buenos_Aires',
    'client_booking_lead_minutes' => 120,
    'client_cancellation_lead_minutes' => 1440,
    'reminder_hours' => (int) env('APPOINTMENT_REMINDER_HOURS', 24),
    'email_notifications' => (bool) env('APPOINTMENT_EMAIL_NOTIFICATIONS', false),
];
