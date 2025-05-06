<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\OrganizationName;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyAnswerUser;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(Survey $survey)
    {
        $questions = $survey->questions;

        $hierarchies = $survey->company->organizationHierarchies; // hierarchiesの取得方法を修正

        return view('survey.start', [
            'survey' => $survey,
            'questions' => $questions,
            'hierarchies' => $hierarchies, // 変更
        ]);
    }

    public function store(Request $request, Survey $survey)
    {
        try {
            $request->validate([
                'department_id' => 'required|exists:organization_names,id',
                'answers' => 'required|array',
                'answers.*' => 'required|integer|between:1,5',
            ]);

            $questionCount = $survey->questions()->count();
            if (count($request->answers) !== $questionCount) {
                return response()->json([
                    'error' => 'すべての質問に回答してください',
                ], 422);
            }

            $companyId = $survey->company_id;

            $orgName = OrganizationName::where('id', $request->department_id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            $surveyAnswerUser = SurveyAnswerUser::create([
                'survey_id' => $survey->id,
                'organization_hierarchy_id' => $orgName->organization_hierarchy_id,
                'organization_names_id' => $orgName->id,
            ]);

            foreach ($request->answers as $questionId => $answerContent) {
                SurveyAnswer::create([
                    'survey_id' => $survey->id,
                    'question_id' => $questionId,
                    'survey_answer_user_id' => $surveyAnswerUser->id,
                    'answer_content' => $answerContent,
                ]);
            }

            return response()->json(['redirect' => route('survey.thanks')]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'サーバーエラーが発生しました',
                'details' => $e->getMessage(),
            ], 300);
        }
    }
}
