<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponseStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'company_id',
        'organization_hierarchy_id',
        'organization_names_id',
        'sent_count',
        'answered_count',
        'response_rate',
        'collected_at',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
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
}
