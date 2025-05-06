<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SurveyQuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'text' => '顧客基盤の安定性',
                'document' => '当社は長期間にわたって安定した顧客関係を築き、維持できていると思いますか。',
            ],
            [
                'text' => '理念戦略へ納得感',
                'document' => '従業員の皆さんに当社のビジョンや戦略に共感し、それに対する理解と信頼を持ってもらうことはできていますか。',
            ],
            [
                'text' => '社会的貢献',
                'document' => '当社が社会的責任を果たし、地域社会や社会全体へ積極的に貢献している行為に満足していますか。',
            ],
            [
                'text' => '責任と顧客・社会へ貢献',
                'document' => '当社は顧客への約束を守り、社会に対しても積極的に貢献できていると思いますか。',
            ],
            [
                'text' => '連帯感と相互尊重',
                'document' => '従業員間で団結力と互いの価値観を尊重する文化があると思いますか。',
            ],
            [
                'text' => '魅力的な上司・同僚',
                'document' => '職場において、尊敬できる上司や魅力的な同僚がいると思いますか。',
            ],
            [
                'text' => '勤務地や会社設備魅力',
                'document' => '勤務地の立地や会社の設備が充実していて働きやすい環境が整っていると思いますか。',
            ],
            [
                'text' => '評価・給与と柔軟な働き方',
                'document' => '公正な評価と適正な給与、柔軟な勤務体制が提供されていると思いますか。',
            ],
            [
                'text' => '顧客ニーズや事業戦略伝達',
                'document' => '顧客の要望や企業の事業戦略が従業員に明確に伝えられていると思いますか。',
            ],
            [
                'text' => '上司や会社から理解',
                'document' => '従業員の意見や状況に対して、上司や会社が理解と支持を示していると思いますか。',
            ],
            [
                'text' => '公平な評価',
                'document' => '従業員の業績や行動が公正な基準によって評価されていると思いますか。',
            ],
            [
                'text' => '上司から適切な教育・支援',
                'document' => '上司が従業員の成長を支援し、必要な知識やスキルの提供を行っていると思いますか。',
            ],
            [
                'text' => '顧客期待を上回る提案',
                'document' => '当社の従業員は、顧客の期待を超える提案やサービスを提供していると思いますか。',
            ],
            [
                'text' => '具体的な目標共有',
                'document' => '会社の目標が明確であり、それが従業員と共有されていると思いますか。',
            ],
            [
                'text' => '未来に向けた活動',
                'document' => '当社は将来の成功に向けて戦略的な活動を行っていると思いますか。',
            ],
            [
                'text' => 'ナレッジ標準化',
                'document' => '当社が持つ知識や情報が整理され、効率的に活用されていると思いますか。',
            ],
        ];

        $data = collect($questions)->map(function ($q, $i) {
            return [
                'survey_id' => 1,
                'question_number' => $i + 1,
                'question_text' => $q['text'],
                'question_document' => $q['document'],
                'question_type' => 'choice',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ];
        })->toArray();

        DB::table('survey_questions')->insert($data);
    }
}
