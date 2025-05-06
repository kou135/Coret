<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'question_id',
        'survey_answer_user_id',
        'answer_content',
        'text_answer',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    public function surveyAnswerUser(): BelongsTo
    {
        return $this->belongsTo(SurveyAnswerUser::class);
    }
}
