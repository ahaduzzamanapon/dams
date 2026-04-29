<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appointment = $this->appointment;
        $doctor = $appointment->doctor;

        return (new MailMessage)
            ->subject('Appointment Confirmed — DAMS Medical Center')
            ->greeting("Dear {$appointment->patient_name},")
            ->line('Your appointment has been **confirmed**. Please find your details below:')
            ->line("**Doctor:** {$doctor->name} ({$doctor->designation})")
            ->line("**Date:** {$appointment->appointment_date->format('d M Y')} at {$appointment->slot_time}")
            ->line('**Please arrive 10 minutes before your scheduled time.**')
            ->line('If you need to cancel or reschedule, please call us immediately.')
            ->salutation('— DAMS Medical Center');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'patient_name' => $this->appointment->patient_name,
            'doctor_name' => $this->appointment->doctor->name,
            'appointment_date' => $this->appointment->appointment_date->toDateString(),
            'slot_time' => $this->appointment->slot_time,
            'status' => $this->appointment->status,
        ];
    }
}
