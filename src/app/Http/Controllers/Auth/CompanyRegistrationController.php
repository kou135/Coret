<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\OrganizationHierarchy;
use App\Models\OrganizationName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyRegistrationController extends Controller
{
    public function showStep1()
    {
        return view('auth.company-register-step1');
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string',
            'employee_count' => 'nullable|string',
            'years_in_business' => 'nullable|string',
            'evaluation_frequency' => 'nullable|string',
            'salary_transparency' => 'nullable|string',
            'evaluation_type' => 'nullable|string',
        ]);

        session(['company_registration.step1' => $validated]);

        return redirect()->route('company.register.step2');
    }

    public function showStep2()
    {
        $organizations = session('company_registration.step2.organizations', []);

        return view('auth.company-register-step2', compact('organizations'));
    }

    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'organizations' => 'required|json',
        ]);

        $organizations = json_decode($validated['organizations'], true);
        session(['company_registration.step2' => ['organizations' => $organizations]]);

        return redirect()->route('company.register.step3');
    }

    public function showStep3()
    {
        $step1Data = session('company_registration.step1');
        $step2Data = session('company_registration.step2');

        if (! $step1Data || ! $step2Data || ! isset($step2Data['organizations'])) {
            return redirect()->route('company.register.step1')->withErrors([
                'error' => 'セッションが切れています。最初からやり直してください。',
            ]);
        }

        $organizations = $step2Data['organizations'];
        $orgTree = $this->buildOrgTree($organizations);
        $orgTreeHtml = $this->renderOrgTreeHtml($orgTree);

        return view('auth.company-register-step3', compact('step1Data', 'orgTreeHtml'));
    }

    public function submitConfirm()
    {
        Log::info('submitConfirm: START');

        $step1Data = session('company_registration.step1');
        $step2Data = session('company_registration.step2');

        if (! $step1Data || ! $step2Data || ! isset($step2Data['organizations'])) {
            Log::error('submitConfirm: session missing');

            return redirect()->route('company.register.step1')->withErrors([
                'error' => 'セッションの有効期限が切れたか、途中のステップが抜けています。',
            ]);
        }

        $organizations = $step2Data['organizations'];

        try {
            DB::beginTransaction();

            do {
                $companyCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            } while (Company::where('company_code', $companyCode)->exists());

            $company = Company::create([
                'company_code' => $companyCode,
                'name' => $step1Data['company_name'],
                'company_size' => $step1Data['employee_count'],
                'business_years' => $step1Data['years_in_business'],
                'salary_transparency' => $step1Data['salary_transparency'],
                'evaluation_frequency' => $step1Data['evaluation_frequency'],
                'evaluation_type' => $step1Data['evaluation_type'],
            ]);

            Log::info('submitConfirm: Company created. ID='.$company->id);

            $hierarchies = [];
            $organizationNameRefs = [];

            foreach ($organizations as $orgIndex => $org) {
                $parentIndex = $org['parentIndex'] ?? null;
                $parentHierarchyId = is_numeric($parentIndex) && isset($hierarchies[$parentIndex])
                    ? $hierarchies[$parentIndex]->id
                    : null;

                $hierarchy = OrganizationHierarchy::create([
                    'company_id' => $company->id,
                    'name' => $org['hierarchy'],
                    'parent_id' => $parentHierarchyId,
                ]);
                $hierarchies[$orgIndex] = $hierarchy;

                foreach ($org['names'] as $nameIndex => $name) {
                    $parentOrgIndex = $org['parentOrgIndexes'][$nameIndex] ?? null;
                    $parentOrgId = $parentOrgIndex !== null && isset($organizationNameRefs[$parentOrgIndex])
                        ? $organizationNameRefs[$parentOrgIndex]->id
                        : null;

                    $organization = OrganizationName::create([
                        'company_id' => $company->id,
                        'organization_hierarchy_id' => $hierarchy->id,
                        'name' => $name,
                        'parent_id' => $parentOrgId,
                    ]);

                    $organizationNameRefs[] = $organization;
                }
            }

            DB::commit();
            session()->forget('company_registration');

            Log::info('submitConfirm: completed successfully, redirecting to step4');

            return redirect()->route('company.register.step4')->with('company_code', $company->company_code);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('submitConfirm: failed', ['exception' => $e->getMessage()]);

            return back()->withErrors(['error' => '登録に失敗しました: '.$e->getMessage()]);
        }
    }

    public function showStep4()
    {
        return view('auth.company-register-step4');
    }

    private function buildOrgTree(array $organizations)
    {
        $flatOrgNames = [];
        foreach ($organizations as $orgIndex => $org) {
            foreach ($org['names'] as $i => $name) {
                $flatOrgNames[] = [
                    'name' => $name,
                    'hierarchy' => $org['hierarchy'],
                    'parent_index' => $org['parentOrgIndexes'][$i] ?? null,
                ];
            }
        }

        $tree = [];
        $indexed = [];

        foreach ($flatOrgNames as $index => &$node) {
            $node['children'] = [];
            $indexed[$index] = &$node;
        }

        foreach ($indexed as $index => &$node) {
            if ($node['parent_index'] !== null && isset($indexed[$node['parent_index']])) {
                $indexed[$node['parent_index']]['children'][] = &$node;
            } else {
                $tree[] = &$node;
            }
        }

        return $tree;
    }

    private function renderOrgTreeHtml(array $tree, int $level = 1): string
    {
        $html = '';
        foreach ($tree as $node) {
            $html .= '<div class="org-item">';
            $html .= '<div class="org-name org-level-'.$level.'">';
            $html .= '<span class="inline-block w-1 h-1 bg-gray-400 rounded-full mr-2"></span>';
            $html .= '<span class="inline-block align-middle">・'.e($node['name']).'</span>';
            $html .= '</div>';

            if (! empty($node['children'])) {
                $html .= '<div class="org-children">';
                $html .= $this->renderOrgTreeHtml($node['children'], $level + 1);
                $html .= '</div>';
            }

            $html .= '</div>';
        }

        return $html;
    }
}
