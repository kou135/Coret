<?php

namespace App\Services;

use App\Models\Company;
use App\Models\OrganizationName;
use App\Models\SurveyMeasure;
use App\Models\SurveyQuestion;
use App\Models\SurveyResult;
use Carbon\Carbon;
use GuzzleHttp\Client;

class AiService
{
    public function getInferenceData($companyId, $surveyId, $organizationId, $questionId)
    {
        $companyInfo = Company::find($companyId);
        $organizationInfo = OrganizationName::find($organizationId);
        $question = SurveyQuestion::find($questionId);

        $surveyResults = SurveyResult::where('survey_id', $surveyId)
            ->where('company_id', $companyId)
            ->where('organization_names_id', $organizationId)
            ->where('question_id', $questionId) // 質問IDでフィルタリング
            ->get();

        $lastMeasure = (new SurveyResultService)->getLastMeasure($companyId, $organizationId, $organizationId, $questionId);

        // アンケート結果に基づく推論
        $surveyPrompt = $this->generateSurveyPrompt($companyInfo, $organizationInfo, $surveyResults);

        // 前回施策に基づく推論（施策情報があれば追加）
        $measurePrompt = $lastMeasure ? $this->generateMeasurePrompt($companyInfo, $organizationInfo, $surveyResults, $lastMeasure) : null;

        $client = new Client;
        try {
            // アンケート結果に基づく推論
            $responseSurvey = $client->post('https://api.openai.com/v1/chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'あなたは因果推論の専門家です。'],
                        ['role' => 'user', 'content' => $surveyPrompt],
                    ],
                    'max_tokens' => 500,
                ],
                'headers' => [
                    'Authorization' => 'Bearer '.config('services.chatgpt.api_key'),
                ],
            ]);
            $bodySurvey = $responseSurvey->getBody()->getContents();
            $dataSurvey = json_decode($bodySurvey, true);

            // 前回施策に基づく推論
            if ($measurePrompt) {
                $responseMeasure = $client->post('https://api.openai.com/v1/chat/completions', [
                    'json' => [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            ['role' => 'system', 'content' => 'あなたは因果推論の専門家です。'],
                            ['role' => 'user', 'content' => $measurePrompt],
                        ],
                        'max_tokens' => 500,
                    ],
                    'headers' => [
                        'Authorization' => 'Bearer '.config('services.chatgpt.api_key'),
                    ],
                ]);
                $bodyMeasure = $responseMeasure->getBody()->getContents();
                $dataMeasure = json_decode($bodyMeasure, true);
            }

            // アンケート結果に基づく推論の処理
            if (isset($dataSurvey['choices'][0]['message']['content'])) {
                $contentSurvey = $dataSurvey['choices'][0]['message']['content'];
                $formattedResponseSurvey = $this->parseSurveyData($contentSurvey);
            } else {
                $formattedResponseSurvey = [
                    'question_number' => "質問{$question->question_number}",
                    'question_text' => "{$question->question_text}",
                    'cause' => '情報なし',
                    'detail' => '情報なし',
                    'data' => '情報なし',
                ];
            }

            // 前回施策に基づく推論の処理
            if (isset($dataMeasure['choices'][0]['message']['content'])) {
                $contentMeasure = $dataMeasure['choices'][0]['message']['content'];
                $formattedResponseMeasure = $this->parseMeasureData($contentMeasure);
            } else {
                $formattedResponseMeasure = [
                    'question_number' => "質問{$question->question_number}",
                    'question_text' => "{$question->question_text}",
                    'changeCause' => '情報なし',
                    'chance' => '情報なし',
                ];
            }

            return [
                'surveyInference' => $formattedResponseSurvey, // アンケート結果の推論
                'measureInference' => $formattedResponseMeasure, // 前回施策の推論
            ];

        } catch (\Exception $e) {
            return response()->json(['error' => 'AI APIエラー: '.$e->getMessage()], 500);
        }
    }

    private function parseSurveyData($responseContent)
    {
        // レスポンスの前後の空白や改行を取り除く
        $responseContent = trim($responseContent);

        // 正規表現で「原因」「詳細」「データ」を抽出
        // 正規表現を改善して改行を考慮
        // 【原因】から【詳細】までの内容を抽出

        preg_match('/【原因】\s*:\s*(.*?)\s*【詳細】/', $responseContent, $causeMatch);

        // 【詳細】から【データ】までの内容を抽出
        preg_match('/【詳細】\s*:\s*(.*?)\s*【データ】/', $responseContent, $detailMatch);

        // 【データ】の内容を抽出
        // タスク部分: 改行を保持してタスクを抽出
        preg_match_all('/【データ】:\s*(.*?)(?=\s*■|$)/s', $responseContent, $dataMatch);
        $data = implode("\n", $dataMatch[1] ?? []);

        // それぞれの部分を取得
        $cause = $causeMatch[1] ?? '原因が見つかりません';
        $detail = $detailMatch[1] ?? '詳細が見つかりません';

        return [
            'cause' => $cause,
            'detail' => $detail,
            'data' => $data,
        ];
    }

    private function parseMeasureData($responseContent)
    {
        // レスポンスの前後の空白や改行を取り除く
        $responseContent = trim($responseContent);

        // 正規表現で「原因」「詳細」「データ」を抽出
        // 正規表現を改善して改行を考慮
        // 【原因】から【詳細】までの内容を抽出

        preg_match('/【変動の要因】\s*:\s*(.*?)\s*【継続の可能性】/', $responseContent, $changeCauseMatch);

        // 【詳細】から【データ】までの内容を抽出
        preg_match('/【継続の可能性】\s*:\s*(.*)/s', $responseContent, $chanceMatch);

        $changeCause = $changeCauseMatch[1] ?? '原因が見つかりません';
        $chance = $chanceMatch[1] ?? '詳細が見つかりません';

        return [
            'changeCause' => $changeCause,
            'chance' => $chance,
        ];
    }

    // アンケート結果に基づくプロンプト生成
    private function generateSurveyPrompt($companyInfo, $organizationInfo, $surveyResults)
    {
        $firstResult = $surveyResults->first();
        $question = SurveyQuestion::find($firstResult->question_id);

        // アンケート結果に基づくプロンプト生成
        $prompt = "以下は従業員アンケートの設問です。この設問の満点の場合は満点の、満点じゃない場合は満点じゃない予想される原因・その根拠（仮説を含む）・データを提示してください。ちなみに満点は100点です。\n";
        $prompt .= '会社情報: '.json_encode($companyInfo)."\n";
        $prompt .= '組織情報: '.json_encode($organizationInfo)."\n";
        $prompt .= "アンケート結果:\n";
        foreach ($surveyResults as $result) {
            $prompt .= "質問{$question->question_number}: {$question->question_text} - 平均スコア: {$result->average_score}/5\n";
        }

        // 最後に出力形式の指示を追加
        $prompt .= "\n以下の出力形式で返答してください。\n";
        $prompt .= "【原因】:\n";
        $prompt .= "原因を20文字以内で簡潔に述べる\n\n";
        $prompt .= "【詳細】:\n";
        $prompt .= "原因に至る詳細な理由を200文字程度で説明する\n\n";
        $prompt .= "【データ】:\n";
        $prompt .= "データとして、会社情報、組織情報、平均スコアの差異などを提示する\n";

        return $prompt;
    }

    // 前回施策に基づくプロンプト生成
    private function generateMeasurePrompt($companyInfo, $organizationInfo, $surveyResults, $lastMeasure)
    {
        $firstResult = $surveyResults->first();
        $question = SurveyQuestion::find($firstResult->question_id);

        $prompt = "以下は従業員アンケートの設問と前回の施策の情報です。施策の情報を元に企業の情報などから点数が上がった場合は上がった要因を、下がったら下がっている要因を提示してください。絶対敬語でお願いします。\n";
        $prompt .= '会社情報: '.json_encode($companyInfo)."\n";
        $prompt .= '組織情報: '.json_encode($organizationInfo)."\n";
        $prompt .= "アンケート結果:\n";
        foreach ($surveyResults as $result) {
            $prompt .= "質問{$question->question_number}: {$question->question_text} - 平均スコア: {$result->average_score}/5\n";
        }

        if ($lastMeasure) {
            $prompt .= "\n施策情報:\n";
            $prompt .= "施策タイトル: {$lastMeasure->measure_title}\n";
            $prompt .= "施策内容: {$lastMeasure->measure_description}\n";
            $prompt .= '施策期間: '.Carbon::parse($lastMeasure->start_date)->format('Y-m-d').' 〜 '.Carbon::parse($lastMeasure->end_date)->format('Y-m-d')."\n";
        } else {
            $prompt .= "\n前回施策はありません\n";
        }

        $prompt .= "\n以下の出力形式で返答してください。\n";
        $prompt .= "【変動の要因】:\n";
        $prompt .= "要因を仮説込みで70文字程度で述べる\n\n";
        $prompt .= "【継続の可能性】:\n";
        $prompt .= "今の組織の情報などと照らし合わせて施策継続の見込みはありそうかを40文字程度で述べる\n\n";

        return $prompt;
    }

    // generateChatResponse メソッド
    public function getAiProposal(string $questionText, string $aiInsight)
    {
        $prompt = $this->aiProposalPrompt($questionText, $aiInsight);
        $client = new Client;

        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'あなたは施策立案の専門家です。'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 500,
                ],
                'headers' => [
                    'Authorization' => 'Bearer '.config('services.chatgpt.api_key'),
                ],
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            // レスポンスが正しく含まれているか確認
            if (isset($data['choices'][0]['message']['content'])) {
                $content = $data['choices'][0]['message']['content'];

                // 正規表現で内容をパース
                $parsedData = $this->parseAiResponse($content);

                // 整形したデータを返す
                $formattedProposalResponse = [
                    'title' => $parsedData['title'] ?? '',
                    'description' => $parsedData['description'] ?? '',
                    'period' => $parsedData['period'] ?? '',
                    'tasks' => $parsedData['tasks'] ?? '',
                ];
            } else {
                // APIレスポンスに予期せぬエラーがあった場合
                $formattedProposalResponse = [
                    'title' => 'エラーが発生しました',
                    'description' => '詳細情報はありません',
                    'period' => '不明',
                    'tasks' => '不明',
                ];
            }

        } catch (\Exception $e) {
            $formattedProposalResponse = [
                'title' => 'エラーが発生しました',
                'description' => '詳細情報はありません',
                'period' => '不明',
                'tasks' => '不明',
            ];

            return response()->json(['error' => 'AI APIエラー: '.$e->getMessage()], 500);
        }

        return $formattedProposalResponse;
    }

    private function parseAiResponse($responseContent)
    {
        // 施策名: 改行や余分なスペースを取り除く
        preg_match('/■施策名: (.*?)■実施内容:/s', $responseContent, $titleMatch);
        $title = isset($titleMatch[1]) ? preg_replace('/\s+/', ' ', trim($titleMatch[1])) : '施策名が見つかりません';

        // 実施内容: 改行や余分なスペースを取り除く
        preg_match('/■実施内容: (.*?)■実施期間:/s', $responseContent, $descriptionMatch);
        $description = isset($descriptionMatch[1]) ? preg_replace('/\s+/', ' ', trim($descriptionMatch[1])) : '実施内容が見つかりません';

        // 実施期間: 改行や余分なスペースを取り除く
        preg_match('/■実施期間: (.*?)■タスク:/s', $responseContent, $periodMatch);
        $period = isset($periodMatch[1]) ? preg_replace('/\s+/', ' ', trim($periodMatch[1])) : '実施期間が見つかりません';

        // タスク部分: 改行を保持してタスクを抽出
        preg_match_all('/■タスク:\s*(.*?)(?=\s*■|$)/s', $responseContent, $tasksMatch);
        $tasks = implode("\n", $tasksMatch[1] ?? []);

        // 必要なデータを返す
        return [
            'title' => $title,  // 改行と余分なスペースを取り除いた施策名
            'description' => $description,  // 改行と余分なスペースを取り除いた実施内容
            'period' => $period,  // 改行と余分なスペースを取り除いた実施期間
            'tasks' => $tasks,
        ];
    }

    public function aiProposalPrompt(string $questionText, string $aiInsight)
    {
        // AIに送るプロンプトを作成
        $prompt = "あなたは施策立案の専門家です。従業員サーベイの結果から、施策対象となる項目とその項目に対する推論の内容を送ります。この2つの情報を元に1つ施策を提示してください。\n";
        $prompt .= "■施策対象質問タイトル: {$questionText}\n";
        $prompt .= "■推論内容: {$aiInsight}\n";
        $prompt .= "以下の出力形式で返答してください。\n";
        $prompt .= "■施策名:（施策のタイトル...18文字以内）\n";
        $prompt .= "■実施内容:（施策の概要...100文字)\n";
        $prompt .= "■実施期間:（およそでいいので）\n";
        $prompt .= "■タスク:（施策完了するまでのステップ事項...箇条書きで）\n";

        // AIにプロンプトを送る
        return $prompt;
    }

    // generateChatResponse メソッド
    public function getMeasureAiProposal(string $questionText, string $aiInsight, int $measureId)
    {
        $prompt = $this->aiMeasureProposalPrompt($questionText, $aiInsight, $measureId);
        $client = new Client;

        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'あなたは施策立案の専門家です。'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 500,
                ],
                'headers' => [
                    'Authorization' => 'Bearer '.config('services.chatgpt.api_key'),
                ],
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            // レスポンスが正しく含まれているか確認
            if (isset($data['choices'][0]['message']['content'])) {
                $content = $data['choices'][0]['message']['content'];

                // 正規表現で内容をパース
                $parsedData = $this->parseMeasureAiResponse($content);

                // 整形したデータを返す
                $formattedMeasureProposalResponse = [
                    'lastTitle' => $parsedData['lastTitle'] ?? '',
                    'hypothesis' => $parsedData['hypothesis'] ?? '',
                    'policys' => $parsedData['policys'] ?? '',
                ];
            } else {
                // APIレスポンスに予期せぬエラーがあった場合
                $formattedMeasureProposalResponse = [
                    'lastTitle' => 'エラー',
                    'hypothesis' => '不明',
                    'policys' => '不明',
                ];
            }

        } catch (\Exception $e) {
            $formattedMeasureProposalResponse = [
                'lastTitle' => 'エラー',
                'hypothesis' => '不明',
                'policys' => '不明',
            ];

            return response()->json(['error' => 'AI APIエラー: '.$e->getMessage()], 500);
        }

        return $formattedMeasureProposalResponse;
    }

    private function parseMeasureAiResponse($responseContent)
    {
        preg_match_all('/■前回施策:(.*?)(?=■見えてきた改善点（仮説）|$)/s', $responseContent, $lastTitleMatch);
        $lastTitle = implode("\n", $lastTitleMatch[1] ?? []);

        // タスク部分: 改行を保持してタスクを抽出
        preg_match_all('/■見えてきた改善点（仮説）:(.*?)(?=■今後の方向性についての質問|$)/s', $responseContent, $hypothesisMatch);
        $hypothesis = implode("\n", $hypothesisMatch[1] ?? []);

        // タスク部分: 改行を保持してタスクを抽出
        preg_match_all('/■今後の方向性についての質問:\s*(.*?)(?=\s*■|$)/s', $responseContent, $policysMatch);
        $policys = implode("\n", $policysMatch[1] ?? []);

        // 必要なデータを返す
        return [
            'lastTitle' => $lastTitle,  // 改行と余分なスペースを取り除いた施策名
            'hypothesis' => $hypothesis,  // 改行と余分なスペースを取り除いた実施内容
            'policys' => $policys,  // 改行と余分なスペースを取り除いた実施期間
        ];
    }

    public function aiMeasureProposalPrompt(string $questionText, string $aiInsight, int $measureId)
    {
        $lastMeasure = SurveyMeasure::where('id', $measureId)->first();

        $prompt = "あなたは施策立案の専門家です。従業員サーベイの結果から、施策対象となる項目とその項目に対する推論の内容を送ります。この情報を元に1つ施策をたたき台として提示してください。\n";
        $prompt .= "施策対象質問タイトル: {$questionText}\n";
        $prompt .= "推論内容: {$aiInsight}\n";
        $prompt .= "前回施策: {$lastMeasure}\n";
        $prompt .= "以下の出力形式で返答してください。\n";
        $prompt .= "■前回施策:\n";
        $prompt .= "施策名: {$lastMeasure->measure_title}\n";
        $prompt .= "実施内容: {$lastMeasure->measure_description}\n";
        $prompt .= "目的:\n";
        $prompt .= "■見えてきた改善点（仮説）:\n";
        $prompt .= "改善点を30文字で3つ提示\n";
        $prompt .= "■今後の方向性についての質問:\n";
        $prompt .= "1. このまま継続する\n";
        $prompt .= "2. 一部内容を修正して継続する\n";
        $prompt .= "3. 新しい施策を立て直したい\n";

        return $prompt;
    }

    public function ChatAiResponse($message, $aiInsight)
    {
        // AIのプロンプトを生成
        $prompt = $this->generateChatPrompt($message, $aiInsight);

        // AI APIを使ってメッセージに基づいた応答を生成
        $client = new Client;
        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'あなたは施策立案の専門家です。'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 500,
                ],
                'headers' => [
                    'Authorization' => 'Bearer '.config('services.chatgpt.api_key'),
                ],
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            // AIの応答が含まれているかを確認
            if (isset($data['choices'][0]['message']['content'])) {
                return $data['choices'][0]['message']['content']; // AIの応答を返す
            }

            return 'AIからの応答が得られませんでした。';

        } catch (\Exception $e) {
            // エラーハンドリング
            return 'エラー: '.$e->getMessage();
        }
    }

    // プロンプトを生成
    private function generateChatPrompt($message, $aiInsight)
    {
        $prompt = "あなたは施策立案サポーターの専門家です。以下の情報を元に、アドバイスをください。\n";
        $prompt .= "施策に関連する問題: {$message}\n";
        $prompt .= "AIの推論内容: {$aiInsight}\n";
        $prompt .= "アドバイスをお願いします。\n";

        return $prompt;
    }
}
