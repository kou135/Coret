<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyAnswerUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'organization_hierarchy_id',
        'organization_names_id',
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

    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
