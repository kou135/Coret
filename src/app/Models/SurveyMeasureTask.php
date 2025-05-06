<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyMeasureTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'measure_id',
        'task_text',
        'status',
        'deadline_date',
    ];

    public function measure(): BelongsTo
    {
        return $this->belongsTo(SurveyMeasure::class, 'measure_id');
    }
}
