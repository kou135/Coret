<?php

namespace App\Mail;

use App\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SurveyInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $survey;

    public $url;

    public function __construct(Survey $survey, string $url)
    {
        $this->survey = $survey;
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject('【ご協力お願いします】アンケートのご案内')
            ->text('emails.survey_invitation_text'); // テキストメール
    }
}
