<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_code',
        'name',
        'company_size',
        'business_years',
        'salary_transparency',
        'evaluation_frequency',
        'evaluation_type',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function organizationHierarchies(): HasMany
    {
        return $this->hasMany(OrganizationHierarchy::class);
    }

    public function organizationNames(): HasMany
    {
        return $this->hasMany(OrganizationName::class);
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    public function surveyResults(): HasMany
    {
        return $this->hasMany(SurveyResult::class);
    }

    public function surveyRecipients(): HasMany
    {
        return $this->hasMany(SurveyRecipient::class);
    }

    public function surveyResponseStats(): HasMany
    {
        return $this->hasMany(SurveyResponseStat::class);
    }

    public function surveyMeasures(): HasMany
    {
        return $this->hasMany(SurveyMeasure::class);
    }
}
