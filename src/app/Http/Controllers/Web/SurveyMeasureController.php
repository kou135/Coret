<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SurveyMeasureRequest;
use App\Models\SurveyMeasureTask;
use App\Services\AiService;
use App\Services\SurveyMeasureService;
use App\Services\SurveyResultService;
use Illuminate\Http\Request;

class SurveyMeasureController extends Controller
{
    protected SurveyMeasureService $surveyMeasureService;

    protected SurveyResultService $surveyResultService;

    protected Aiservice $aiService;

    public function __construct(SurveyMeasureService $surveyMeasureService, SurveyResultService $surveyResultService, AiService $aiService)
    {
        $this->surveyMeasureService = $surveyMeasureService;
        $this->surveyResultService = $surveyResultService;
        $this->aiService = $aiService;
    }

    public function index()
    {
        $measures = $this->surveyMeasureService->getFormattedMeasuresForList();

        return view('measure.index', [
            'measures' => $measures,
        ]);
    }

    public function show($id)
    {
        $id = (int) $id;

        $measure = $this->surveyMeasureService->getDetailById($id);

        $measureEffect = [
            'before' => null,
            'after' => null,
            'score_diff_text' => 'データなし',
        ];

        if ($measure->status === '実施済み') {
            $actualEffect = $this->surveyResultService->getScoreBeforeAfter(
                $measure->company_id,
                $measure->organization_names_id,
                $measure->question_id
            );

            if ($actualEffect) {
                $measureEffect = $actualEffect;
            }
        }

        $progress = $this->surveyMeasureService->calculateProgress($measure);

        return view('measure.show', [
            'measure' => $measure,
            'measureEffect' => $measureEffect,
            'totalTasks' => $progress['totalTasks'],
            'completedTasksCount' => $progress['completedTasksCount'],
            'remainingTasks' => $progress['remainingTasks'],
        ]);
    }

    public function create(Request $request, $questionId)
    {
        $measureId = $request->get('measureId', '');
        $aiInsight = $request->get('aiInsight', '');

        $affiliation = session('admin_affiliation');
        $companyId = $affiliation['company_id'];
        $hierarchyId = $affiliation['organization_hierarchy_id'];
        $organizationId = $affiliation['organization_names_id'];
        $userId = 1;
        $surveyId = $companyId;

        $questionText = $this->surveyMeasureService->getQuestionText($questionId);

        $previousAiProposal = $measureId ? $this->aiService->getMeasureAiProposal($questionText, $aiInsight, $measureId) : null;

        if ($previousAiProposal) {
            session(['previousAiProposal' => $previousAiProposal]);
            session()->forget('aiProposal');
        } else {
            $aiProposal = $this->aiService->getAiProposal($questionText, $aiInsight);
            session(['aiProposal' => $aiProposal]);
            session()->forget('previousAiProposal');
        }

        return view('measure.create', [
            'aiInsight' => $aiInsight,
            'userId' => $userId,
            'companyId' => $companyId,
            'organizationHierarchyId' => $hierarchyId,
            'organizationNamesId' => $organizationId,
            'surveyId' => $surveyId,
            'questionId' => $questionId,
            'measureId' => $measureId,
            'questionText' => $questionText,
            'aiProposal' => session('aiProposal'),
            'previousAiProposal' => session('previousAiProposal'),
        ]);
    }

    public function store(SurveyMeasureRequest $request)
    {
        $validated = $request->validated();

        $this->surveyMeasureService->createWithTasks($validated);

        return view('measure.thanks', ['questionId' => $request->get('question_id')])
            ->with('success', '施策を登録しました');
    }

    public function update(Request $request, $measureId, $taskId)
    {
        $task = SurveyMeasureTask::findOrFail($taskId);
        $task->status = '完了';
        $task->save();

        $measure = $task->measure;
        $allTasks = $measure->tasks;
        $completedTasks = $allTasks->where('status', '完了');

        if ($allTasks->count() === $completedTasks->count()) {
            $measure->status = '実施済み';
            $measure->save();
        }

        return redirect()->route('measures.show', ['id' => $measureId]);
    }

    public function send(Request $request, $questionId)
    {
        $message = $request->get('message');
        $aiInsight = $request->get('aiInsight');
        $questionText = $this->surveyMeasureService->getQuestionText($questionId);

        $affiliation = session('admin_affiliation');
        if (! $affiliation) {
            abort(403, '所属情報が見つかりません。');
        }

        $userId = 1;
        $companyId = $affiliation['company_id'];
        $hierarchyId = $affiliation['organization_hierarchy_id'];
        $organizationId = $affiliation['organization_names_id'];
        $surveyId = $companyId;

        $aiResponse = $this->aiService->ChatAiResponse($message, $aiInsight);

        $chatHistory = session()->get('chatHistory', []);
        $chatHistory[] = [
            'user' => $message,
            'ai' => $aiResponse,
        ];
        session(['chatHistory' => $chatHistory]);

        return view('measure.create', [
            'userId' => $userId,
            'companyId' => $companyId,
            'organizationHierarchyId' => $hierarchyId,
            'organizationNamesId' => $organizationId,
            'surveyId' => $surveyId,
            'aiProposal' => session('aiProposal'),
            'previousAiProposal' => session('previousAiProposal'),
            'chatHistory' => $chatHistory,
            'questionId' => $questionId,
            'aiInsight' => $aiInsight,
            'message' => $message,
            'questionText' => $questionText,
        ]);
    }
}
