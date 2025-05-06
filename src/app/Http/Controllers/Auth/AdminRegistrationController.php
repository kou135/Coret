<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\IndustryCategory;
use App\Models\OrganizationHierarchy;
use App\Models\OrganizationName;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminRegistrationController extends Controller
{
    public function showStep1()
    {
        return view('auth.admin-register-step1');
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'company_code' => 'required|digits:4',
        ]);

        $company = Company::where('company_code', $validated['company_code'])->first();

        if (! $company) {
            return back()->withErrors(['company_code' => '企業コードが見つかりません'])->withInput();
        }

        session(['admin_register.company_id' => $company->id]);

        return redirect()->route('admin.register.step2');
    }

    public function showStep2(Request $request)
    {
        $companyId = session('admin_register.company_id');

        $hierarchies = OrganizationHierarchy::with('organizationNames')
            ->where('company_id', $companyId)
            ->get();

        $data = session('admin_register.step2', []);

        return view('auth.admin-register-step2', compact('hierarchies', 'data'));
    }

    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|string|max:255',
            'organizations' => 'required|array',
            'organizations.*' => 'nullable|exists:organization_names,id',
            'organization_size' => 'nullable|string',
            'remote_work_status' => 'nullable|string',
            'flex_time_status' => 'nullable|string',
            'one_on_one_frequency' => 'nullable|string',
            'age_distribution' => 'nullable|string',
            'average_overtime_hours' => 'nullable|string',
            'industry' => 'nullable|array',
            'industry.*' => 'string',
        ]);

        session(['admin_register.step2' => $validated]);

        return redirect()->route('admin.register.step3');
    }

    public function showStep3()
    {
        $step2 = session('admin_register.step2');
        $companyId = session('admin_register.company_id');

        if (! $step2 || ! $companyId) {
            return redirect()->route('admin.register.step2')->withErrors([
                'message' => 'セッションが切れたため、もう一度入力してください。',
            ]);
        }

        $selectedOrganizations = OrganizationName::with('organizationHierarchy')
            ->whereIn('id', array_filter(array_values($step2['organizations'])))
            ->get();

        $company = Company::find($companyId);

        return view('auth.admin-register-step3', compact('step2', 'selectedOrganizations', 'company'));
    }

    public function submitConfirm()
    {
        $step2 = session('admin_register.step2');
        $companyId = session('admin_register.company_id');

        $selectedOrganizationNameId = collect($step2['organizations'])->filter()->last();

        $organizationName = OrganizationName::with('organizationHierarchy')->find($selectedOrganizationNameId);
        $organizationHierarchyId = $organizationName->organizationHierarchy->id ?? null;

        $existingUser = User::where('email', $step2['email'])->first();

        if ($existingUser) {
            return redirect()->route('admin.register.step2')
                ->withErrors(['email' => 'このメールアドレスはすでに登録されています。']);
        }

        $user = User::create([
            'first_name' => $step2['first_name'],
            'last_name' => $step2['last_name'],
            'email' => $step2['email'],
            'password' => Hash::make($step2['password']),
            'company_id' => $companyId,
            'organization_names_id' => $selectedOrganizationNameId,
            'organization_hierarchy_id' => $organizationHierarchyId,
            'position' => $step2['position'],
        ]);

        if ($selectedOrganizationNameId && $organizationName) {
            $organizationName->update([
                'organization_size' => $step2['organization_size'] ?? null,
                'remote_work_status' => $step2['remote_work_status'] ?? null,
                'flex_time_status' => $step2['flex_time_status'] ?? null,
                'one_on_one_frequency' => $step2['one_on_one_frequency'] ?? null,
                'age_distribution' => $step2['age_distribution'] ?? null,
                'average_overtime_hours' => $step2['average_overtime_hours'] ?? null,
            ]);

            if (! empty($step2['industry'])) {
                foreach ($step2['industry'] as $industryName) {
                    IndustryCategory::create([
                        'organization_name_id' => $selectedOrganizationNameId,
                        'name' => $industryName,
                    ]);
                }
            }
        }

        session()->forget('admin_register');

        return redirect()->route('admin.register.step4');
    }

    public function showStep4()
    {
        return view('auth.admin-register-step4');
    }
}
