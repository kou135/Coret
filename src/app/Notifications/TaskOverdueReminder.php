<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverdueReminder extends Notification
{
    public $task;

    /**
     * コンストラクタ
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * 通知方法（今回はメール）
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * メール通知の内容
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('【期限切れ】タスクが未完了のままです')
            ->text('emails.task_overdue_reminder_text', [
                'task_text' => $this->task->task_text,
                'deadline_date' => \Carbon\Carbon::parse($this->task->deadline_date)->format('Y/m/d'),
                'url' => url("/measures/{$this->task->measure->id}"),
            ]);
    }

    /**
     * デフォルトの配列表現（今回は不要）
     */
    public function toArray($notifiable): array
    {
        return [];
    }
}
