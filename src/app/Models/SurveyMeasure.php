<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyMeasure extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'company_id',
        'organization_hierarchy_id',
        'organization_names_id',
        'question_id',
        'measure_title',
        'measure_description',
        'target_scope',
        'user_id',
        'status',
        'start_date',
        'end_date',
        'measure_effect',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function organizationHierarchy(): BelongsTo
    {
        return $this->belongsTo(OrganizationHierarchy::class);
    }

    public function organizationName(): BelongsTo
    {
        return $this->belongsTo(OrganizationName::class, 'organization_names_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(SurveyMeasureTask::class, 'measure_id');
    }
}
