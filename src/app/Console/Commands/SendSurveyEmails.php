<?php

namespace App\Console\Commands;

use App\Mail\SurveyInvitationMail;
use App\Models\Company;
use App\Models\Survey;
use App\Models\SurveyRecipient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSurveyEmails extends Command
{
    protected $signature = 'survey:send-emails';

    protected $description = '全企業の従業員に最新サーベイを3ヶ月に1度メール送信';

    public function handle()
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            $latestSurvey = Survey::where('company_id', $company->id)->latest()->first();
            if (! $latestSurvey) {
                continue;
            }

            $recipients = SurveyRecipient::where('company_id', $company->id)->get();

            foreach ($recipients as $recipient) {
                $url = url("/survey/{$latestSurvey->id}?token=".uniqid());

                Mail::to($recipient->email)->send(new SurveyInvitationMail($latestSurvey, $url));
            }
        }

        $this->info('アンケート送信完了');
    }
}
