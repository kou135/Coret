<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationHierarchy extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'parent_id', // 追加
    ];

    // 🔄 所属する企業
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // 📚 親階層とのリレーション（例：営業部の親は本部）
    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrganizationHierarchy::class, 'parent_id');
    }

    // 📁 子階層とのリレーション（例：営業部の子は第一課）
    public function children(): HasMany
    {
        return $this->hasMany(OrganizationHierarchy::class, 'parent_id');
    }

    // 🏷 組織名とのリレーション
    public function organizationNames(): HasMany
    {
        return $this->hasMany(OrganizationName::class);
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
        return $this->hasMany(SurveyRecipient::class);
    }

    public function surveyResponseStats(): HasMany
    {
        return $this->hasMany(SurveyResponseStat::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
