<?php

namespace App\View\Components;

use App\Models\OrganizationName;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Header extends Component
{
    public string $departmentName;

    public string $userName;

    public ?string $currentStep = null;

    public function __construct()
    {
        $affiliation = session('admin_affiliation');

        if (! $affiliation) {
            abort(403, '所属情報が見つかりません。');
        }
        $companyId = $affiliation['company_id'];
        $hierarchyId = $affiliation['organization_hierarchy_id'];
        $organizationId = $affiliation['organization_names_id'];

        // 仮のユーザーオブジェクトを取得
        $user = User::where('company_id', $companyId)
            ->where('organization_hierarchy_id', $hierarchyId)
            ->where('organization_names_id', $organizationId)
            ->first();

        // ↓ 本番用コードは一時的にコメントアウトしておく
        // $user = Auth::user();

        // ユーザーの部署階層（営業部 > 第2課）を生成
        $departmentName = '';
        if ($user && $user->organization_names_id) {
            $org = OrganizationName::with('parent')->find($user->organization_names_id);
            $names = [];

            while ($org) {
                array_unshift($names, $org->name);
                $org = $org->parent;
            }

            $departmentName = implode(' ', $names);
        }

        $this->departmentName = $departmentName;
        $this->userName = $user ? "{$user->last_name} {$user->first_name}" : '';

        // ここでルート名から currentStep を推測
        $route = Route::currentRouteName();
        $stepMap = [
            'home' => 'step1 課題発見',
            'measures.create' => 'step2 施策立案',
            'measures.index' => 'step3 施策実行',
            'measures.show' => 'step3 施策実行',
            'measures.send' => 'step3 施策実行',
        ];

        $this->currentStep = $stepMap[$route] ?? null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
