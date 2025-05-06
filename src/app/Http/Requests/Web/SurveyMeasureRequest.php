<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class SurveyMeasureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'survey_id' => 'required|exists:surveys,id',
            'company_id' => 'required|exists:companies,id',
            'organization_hierarchy_id' => 'required|exists:organization_hierarchies,id',
            'organization_names_id' => 'required|exists:organization_names,id',
            'question_id' => 'nullable|exists:survey_questions,id',
            'measure_title' => 'required|string|max:255',
            'measure_description' => 'required|string|max:1000',
            'target_scope' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'tasks' => 'nullable|array|max:10',
            'tasks.*.task_text' => 'nullable|string|max:500',
            'tasks.*.deadline_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'survey_id.required' => 'アンケートIDは必須です。',
            'survey_id.exists' => '有効なアンケートIDを指定してください。',

            'company_id.required' => '会社IDは必須です。',
            'company_id.exists' => '有効な会社IDを指定してください。',

            'organization_hierarchy_id.required' => '階層IDは必須です。',
            'organization_hierarchy_id.exists' => '有効な階層IDを指定してください。',

            'organization_names_id.required' => '組織IDは必須です。',
            'organization_names_id.exists' => '有効な組織IDを指定してください。',

            'question_id.exists' => '有効な質問IDを指定してください。',

            'measure_title.required' => '施策タイトルを入力してください。',
            'measure_title.max' => '施策タイトルは255文字以内で入力してください。',

            'measure_description.required' => '施策概要を入力してください。',
            'measure_description.max' => '施策概要は1000文字以内で入力してください。',

            'target_scope.max' => '対象範囲は255文字以内で入力してください。',

            'user_id.required' => 'ユーザーIDは必須です。',
            'user_id.exists' => '有効なユーザーIDを指定してください。',

            'start_date.date' => '開始日は日付形式で入力してください。',
            'end_date.date' => '終了日は日付形式で入力してください。',
            'end_date.after_or_equal' => '終了日は開始日以降の日付を入力してください。',

            'tasks.required' => '少なくとも1件のタスクを入力してください。',
            'tasks.array' => 'タスクの形式が正しくありません。',
            'tasks.max' => 'タスクは最大10個まで入力できます。',
            'tasks.*.task_text.max' => '各タスク内容は500文字以内で入力してください。',
            'tasks.*.task_text.nullable' => 'タスク内容は空でも問題ありません。',
        ];
    }
}
