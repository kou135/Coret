<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AiService;
use App\Services\SurveyResultService;
use Illuminate\Http\Request;

class SurveyResultController extends Controller
{
    protected $surveyResultService;

    protected $aiService;

    public function __construct(SurveyResultService $surveyResultService, AiService $aiService)
    {
        $this->surveyResultService = $surveyResultService;
        $this->aiService = $aiService;
    }

    public function show(Request $request, $questionId = 1)
    {
        $affiliation = session('admin_affiliation');

        if (! $affiliation) {
            abort(403, '所属情報が見つかりません。');
        }

        $companyId = $affiliation['company_id'];
        $hierarchyId = $affiliation['organization_hierarchy_id'];
        $organizationId = $affiliation['organization_names_id'];
        $surveyId = $companyId;

        $totalBeforeAfter = $this->surveyResultService->getTotalBeforeAfterScore(
            $companyId,
            $hierarchyId,
            $organizationId
        );

        $itemBeforeAfter = $this->surveyResultService->getItemBeforeAfterScores(
            $companyId,
            $hierarchyId,
            $organizationId
        );

        $scoreMap = $this->surveyResultService->getScoreMapData(
            $companyId,
            $hierarchyId,
            $organizationId
        );

        $lastMeasure = $this->surveyResultService->getLastMeasure($companyId, $hierarchyId, $organizationId, $questionId);

        $inferenceData = $this->aiService->getInferenceData($companyId, $surveyId, $organizationId, $questionId);

        $rates = $this->surveyResultService->getrate(
            $companyId,
        );

        $questionData = $this->surveyResultService->getQuestionData($questionId);

        return view('home', [
            'companyId' => $companyId,
            'hierarchyId' => $hierarchyId,
            'organizationId' => $organizationId,
            'questionId' => $questionId,
            'totalBeforeAfter' => $totalBeforeAfter,
            'itemBeforeAfter' => $itemBeforeAfter->values(),
            'scoreMap' => $scoreMap->values(),
            'lastMeasure' => $lastMeasure,
            'inferenceData' => $inferenceData,
            'rates' => $rates,
            'questionData' => $questionData,
        ]);
    }
}
