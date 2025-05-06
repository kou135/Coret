<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDeadlineReminder extends Notification
{
    use Queueable;

    public $task;

    /**
     * Create a new notification instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('【リマインド】タスクの締切が近づいています')
            ->text('emails.task_deadline_reminder_text', [
                'task_text' => $this->task->task_text,
                'deadline_date' => \Carbon\Carbon::parse($this->task->deadline_date)->format('Y/m/d'),
                'url' => url("/measures/{$this->task->measure->id}"),
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_text' => $this->task->task_text,
            'deadline_date' => $this->task->deadline_date,
        ];
    }
}
