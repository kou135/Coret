<?php

namespace App\Console\Commands;

use App\Models\SurveyMeasureTask;
use App\Notifications\TaskDeadlineReminder;
use App\Notifications\TaskOverdueReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendTaskDeadlineReminders extends Command
{
    protected $signature = 'notify:task-deadlines';

    protected $description = '3日前のタスクに通知を送信';

    public function handle()
    {
        $targetDate = Carbon::today()->addDays(3);

        $tasks = SurveyMeasureTask::with('measure.user')
            ->where('status', '未完了')
            ->whereDate('deadline_date', $targetDate)
            ->get();

        foreach ($tasks as $task) {
            $user = $task->measure->user;
            if ($user && $user->email) {
                $user->notify(new TaskDeadlineReminder($task));
            }
        }

        $overdueTasks = SurveyMeasureTask::with('measure.user')
            ->where('status', '未完了')
            ->whereDate('deadline_date', '<', Carbon::today())
            ->get();

        foreach ($overdueTasks as $task) {
            $user = $task->measure->user;
            if ($user && $user->email) {
                $user->notify(new TaskOverdueReminder($task));
            }
        }

        $this->info('リマインド通知（3日前・期限切れ）を送信しました');
    }
}
