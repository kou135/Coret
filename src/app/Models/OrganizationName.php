<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationName extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'organization_hierarchy_id',
        'parent_id',
        'name',
        'organization_size',
        'remote_work_status',
        'flex_time_status',
        'one_on_one_frequency',
        'age_distribution',
        'average_overtime_hours',
    ];

    // 所属企業
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // 所属の階層（部・課など）
    public function organizationHierarchy(): BelongsTo
    {
        return $this->belongsTo(OrganizationHierarchy::class);
    }

    // 親の組織名（例: 営業部 ← 第一課）
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // 子の組織名（例: 営業部 → 第一課、第二課）
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function surveyAnswerUsers(): HasMany
    {
        return $this->hasMany(SurveyAnswerUser::class);
    }

    public function surveyResults(): HasMany
    {
        return $this->hasMany(SurveyResult::class);
    }

    public function surveyRecipients(): HasMany
    {
        return $this->hasMany(SurveyRecipient::class, 'organization_names_id');
    }

    public function surveyResponseStats(): HasMany
    {
        return $this->hasMany(SurveyResponseStat::class, 'organization_names_id');
    }

    // usersテーブルに organization_name_id があるため
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
