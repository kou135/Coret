@extends('layouts.app-layout')

@section('title', 'ホーム')

@section('content')
    <div class="container mx-auto py-6 px-4">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- ヘッダー部分 -->
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-2xl font-medium flex items-center text-gray-700">
                    <svg class="w-6 h-6 mr-2 text-teal-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18"></path>
                        <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path>
                    </svg>
                    <div class="flex">
                        <div class="text-2xl font-bold text-gray">組織状態を把握しましょう</div>
                    </div>
                </h1>
            </div>

            <!-- 上部セクション：総合スコアと項目別スコア -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                <!-- 左側：総合スコア -->
                <div class="bg-white p-4 rounded-lg">
                    <div class="flex gap-x-3 items-center">
                        <h2 class="text-lg font-medium text-gray">今回のアンケートの点数</h2>
                        <x-question-mark tooltipClass="ml-[80px] text-gray text-sm font-medium">
                            このスコアは、今回のアンケートで全項目の平均点を示しています。<br>
                            以下の基準をもとに、組織全体の状態を把握できます。<br>
                            <br>
                            非常に良い（80〜100）: 理想的な状態です。この状態を保ちましょう。<br>
                            良好（60〜79）: 一見良い状態ですが、課題の芽がないか確認しておきましょう。<br>
                            注意が必要（40〜59）: 一部に課題が見られます。改善の方向性を検討する段階です。<br>
                            改善が急務（20〜39）: 明確な課題があります。具体的な対策が必要です。<br>
                            重大な課題（1〜19）: すぐに対応すべき重大な課題です。優先的に改善に取り組みましょう。
                        </x-question-mark>
                    </div>

                    <div class="flex justify-center">
                        <div class="flex">
                            <!-- 円グラフ -->
                            <div class="relative w-56 h-56 mt-5">
                                <canvas id="totalScoreChart"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <div class="flex">
                                        <!-- スコアに20倍を適用しない -->
                                        <div class="text-5xl font-black text-bar">
                                            {{ $totalBeforeAfter->last()['after'] ?? 0 }}</div>
                                        <div class="text-sm text-bar self-end">点</div>
                                    </div>
                                    @if (count($totalBeforeAfter) > 0 && isset($totalBeforeAfter->last()['before']))
                                        @php
                                            $currentScore = $totalBeforeAfter->last()['after'] ?? 0; // 20倍なし
                                            $previousScore = $totalBeforeAfter->last()['before'] ?? 0; // 20倍なし
                                            $difference = $currentScore - $previousScore;
                                            $arrowClass = $difference >= 0 ? 'text-bar' : 'text-arrowred';
                                            $arrowIcon = $difference >= 0 ? '↑' : '↓';
                                        @endphp
                                        <div class="text-sm mt-1">
                                            <span
                                                class="{{ $arrowClass }}">{{ $arrowIcon }}{{ abs($difference) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- 回答率 -->
                            <div class="ml-2 mt-56 flex items-center">
                                <div class="text-sm font-medium text-gray">回答率：</div>
                                    <div class="text-sm font-bold text-bar"><span class="text-lg">{{ $rates['responserate'][1] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Coret botメッセージ -->
                    <div class="flex items-start mt-6">
                        <!-- CoretBot 画像 -->
                        <div class="pt-6">
                            <img src="{{ asset('img/CoretBot.svg') }}" alt="CoretBot">
                        </div>
                        <div class="mt-6 ml-4 border border-bar rounded-b-lg rounded-tr-lg p-7 flex justify-center w-4/5">
                            <div class="text-sm text-gray font-roboto">
                                @php
                                    $score = $totalBeforeAfter->last()['after'] ?? 0;
                                @endphp
                                @if ($score >= 80)
                                    <p>素晴らしい結果です！この調子で維持し、さらに改善を進めていきましょう。施策の強化とモチベーション維持を意識して取り組む方法を、AIの提案を参考にしながら考えて見ましょう。</p>
                                @elseif ($score >= 60)
                                    <p>良好な結果ですが、若干の課題も見られます。引き続き改善点の洗い出しや施策の強化に取り組んで、更なる改善を目指しましょう。その際、AIの提案も参考にしてみてください。</p>
                                @elseif ($score >= 40)
                                    <p>改善が必要な領域がいくつかあります。特に注視すべき施策を選び、優先的に改善を行うことが求められます。AIの提案を参考に、具体的な施策を考えて見ましょう。</p>
                                @elseif ($score >= 20)
                                    <p>現状では大きな課題が残っています。今すぐにでも施策を見直し、根本的な改善策を検討・実行する必要があります。AIの提案も参考にしながら検討を進めましょう。</p>
                                @else
                                    <p>組織体制の早急な見直しが求められています。スコアの低い項目を中心に、課題解決に向けた具体的な施策を速やかに打ち出す必要があります。AIの提案も参考にしながら検討を進めましょう。</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex gap-x-3 items-center pt-4">
                        <div class="text-lg font-medium text-gray">スコアマップ</div>
                        <x-question-mark tooltipClass="ml-[80px] text-gray text-sm font-medium">
                            スコアマップは、スコアの高さと前回からの変化を組み合わせて、<br>
                            各項目の状態をひと目で把握できる図です。スコアが低くても<br>
                            変化がなければ重要な問題ではないこともあります。<br>
                            一方、スコアが上昇した項目は改善の成果を示している可能性があり、<br>
                            今後の施策に役立ちます。点にカーソルを合わせると、各項目の詳細を確認できます。
                        </x-question-mark>
                    </div>

                    <!-- スコアマップ -->
                    <div class="relative w-[450px] h-[450px]">
                        <!-- 縦ラベル -->
                        <div
                            class="absolute -top-6 left-2/5 text-xs bg-white text-lightGreen border-2 border-lightGreen z-10">
                            <div class="p-1 flex items-center gap-1 font-semibold">
                                <img src="{{ asset('img/goodhand.svg') }}" alt="高い" class="w-4 h-4">
                                スコアが高い
                            </div>
                        </div>
                        <div
                            class="absolute -bottom-1 left-2/5 text-xs  bg-white text-lightGreen border-2 border-lightGreen z-10">
                            <div class="p-1 flex items-center gap-1 font-semibold">
                                <img src="{{ asset('img/badhand.svg') }}" alt="低い" class="w-4 h-4">
                                スコアが低い
                            </div>
                        </div>

                        <!-- 横ラベル -->
                        <div
                            class="absolute top-2/5 -left-1/11 text-xs bg-white text-lightGreen border-2 border-lightGreen z-10">
                            <div class="p-1 flex items-center gap-1 font-semibold">
                                <img src="{{ asset('img/badface.svg') }}" alt="悪化" class="w-4 h-4">
                                悪化傾向
                            </div>
                        </div>
                        <div
                            class="absolute top-2/5 -right-1/6 text-xs bg-white text-lightGreen border-2 border-lightGreen z-10">
                            <div class="p-1 flex items-center gap-1 font-semibold">
                                <img src="{{ asset('img/goodface.svg') }}" alt="改善" class="w-4 h-4">
                                改善傾向
                            </div>
                        </div>

                        <!-- グラフ本体 -->
                        <canvas id="scoreMapChart" class="absolute inset-0" width="430" height="430"></canvas>
                    </div>

                </div>
            </div>

            <!-- 下部セクション：今、解決すべき問題を見つけましょう -->
            <div class="mt-10">
                <h2 class="text-lg font-medium text-gray-700 mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <div class="text-2xl font-bold text-gray">改善のヒントを見つけましょう</div>
                </h2>
                <div class="ml-5 text-gray">項目を選択して、原因を考えてみましょう</div>
                <div class="flex gap-2 pt-6">
                    <div class="bg-red-100 w-8 h-5"></div>
                    <div class="text-gray text-sm">平均点が低い項目</div>
                </div>

                <!-- 項目別スコアグラフ -->
                <div class="flex justify-between">
                    <div class="w-[39%] mt-7">
                        <div id="itemScoreChart"></div>
                    </div>
                    <div class="w-[59%] border-2 border-bar rounded">
                        @if ($inferenceData && count($inferenceData) > 0)
                            <div class="px-8 py-6">
                                <div class="">
                                    <div class="flex gap-7">
                                        <div class="text-gray text-xl font-semibold">項目{{ $questionData->question_number ?? '' }}</div>
                                        <div class="text-bar text-xl font-semibold">{{ $questionData->question_text ?? '' }}</div>
                                    </div>
                                    <div class="text-gray text-sm pt-3">設問文：{{ $questionData->question_document ?? '' }}</div>
                                    <div class="bg-box w-full h-8 flex gap-2 justify-center items-center mt-6 rounded">
                                        <div class="text-placeholder text-xs font-bold">原因を考える</div>
                                        <x-question-mark tooltipClass="ml-[10px] text-gray text-sm font-medium">
                                            選択した項目に関して、AIが本質的な原因を探り出します。<br>
                                            アンケート結果に加え、事前に管理者が登録した企業情報や組織構造、<br>
                                            働き方などのデータをもとに、予測される原因を提示します。<br>
                                            提示された根拠とデータを参考に、自身の組織に当てはまる原因か<br>
                                            どうかを考えてみましょう。
                                        </x-question-mark>
                                    </div>
                                    <div class="border border-placeholder rounded-md p-4 mt-4">
                                        <div>
                                            <div class="text-lightGray text-sm">主な原因：</div>
                                            <div class="text-lg font-semibold text-gray">
                                                {{ $inferenceData['surveyInference']['cause'] ?? '' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-lightGray pt-2 text-sm">原因の詳細：</div>
                                            <div class="text-base text-gray font-poppins">
                                                {{ $inferenceData['surveyInference']['detail'] ?? '' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-lightGray text-sm pt-2">原因の根拠:</div>
                                            <div class="text-base text-gray font-poppins">{!! nl2br(e($inferenceData['surveyInference']['data'])) !!}</div>
                                        </div>
                                        <div class="flex items-center pt-5 justify-between">
                                            <div class="flex items-center gap-6">
                                                <div><img src="{{ asset('img/stop.svg') }}" alt=""></div>
                                                <div class="text-bar text-xs font-semibold">AIが提示したこの原因は<br>あなたの部下に当てはまると思いますか？</div>
                                            </div>
                                            <button id="createMeasureBtn" class="bg-bar text-white px-12 py-2 rounded font-semibold text-sm flex justify-center hover:bg-hoverBar"
                                                data-question-id="{{ $questionId ?? 5 }}"
                                                data-ai-aiInsight="原因{{ $inferenceData['surveyInference']['cause'] ?? '' }}・根拠{{ $inferenceData['surveyInference']['detail'] ?? '' }}・根拠の元になったデータ{{ $inferenceData['surveyInference']['data'] ?? '' }}">
                                                この原因に対して<br>施策を立案する
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="pt-6">
                                            <div class="bg-box w-full h-8 flex justify-center items-center mt-6 mb-4 rounded">
                                                <div class="text-placeholder text-xs font-bold">前回施策</div>
                                            </div>
                                            @if ($inferenceData['measureInference'] && $lastMeasure)
                                                <div class="py-2 text-center bg-red-100 flex justify-center border border-arrowRed rounded-md mb-1 gap-2">
                                                    <img src="{{ asset('img/folder_check.svg') }}" alt="">
                                                    <div class="text-arrowRed text-sm font-poppins font-semibold">前回施策を行いました。効果を測定しましょう</div>
                                                </div>
                                            <div>
                                                <div class="border border-placeholder rounded-md p-4">
                                                    <div class="text-placeholder text-sm">日付</div>
                                                    <div class="flex gap-6 items-center pt-2">
                                                        <div class="text-gray text-xl">{{ $lastMeasure->measure_title }}</div>
                                                    </div>
                                                    <hr class="border-t-3 border-dotted border-placeholder my-3">
                                                    <div>
                                                        <div>
                                                            <div class="text-placeholder text-sm pt-2">AIによる総評：</div>
                                                            <div class="text-gray pt-2">
                                                                <div>■変動の要因</div>
                                                                <div>{{ $inferenceData['measureInference']['changeCause'] ?? '' }}</div>
                                                            </div>
                                                            <div class="text-gray pt-2">
                                                                <div>■継続の可能性</div>
                                                                <div>{{ $inferenceData['measureInference']['chance'] ?? '' }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end">
                                                        <button id="updateMeasureBtn" class="bg-bar text-white px-12 py-2 rounded font-semibold text-sm flex justify-center mt-5 hover:bg-hoverBar"
                                                            data-question-id="{{ $questionId ?? 5 }}"
                                                            data-measure-id="{{ $lastMeasure->id }}"
                                                            data-ai-aiInsight="変動の要因{{ $inferenceData['measureInference']['changeCause'] ?? '' }}・継続の可能性{{ $inferenceData['measureInference']['chance'] ?? '' }}">
                                                            前回施策をもとに<br>
                                                            施策を立案する
                                                        </button>
                                                    </div>
                                                </div>
                                                @else
                                                    <div class="flex justify-center py-6 border border-placeholder rounded-md">
                                                        <img src="{{ asset('img/folder.svg') }}" alt="">
                                                        <div class="text-title text-sm font-poppins">前回施策はありません</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-red-500">因果推論データが見つかりませんでした。</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalBeforeAfterData = @json($totalBeforeAfter);
            const itemBeforeAfterData = @json($itemBeforeAfter);
            const scoreMapData = @json($scoreMap);

            renderTotalScoreChart(totalBeforeAfterData);
            renderItemScoreChart(itemBeforeAfterData);
            renderScoreMapChart(scoreMapData);
        });

        document.getElementById('createMeasureBtn').addEventListener('click', function() {
            // ボタンからデータ属性を取得
            const questionId = this.getAttribute('data-question-id'); // ボタンの質問ID
            const aiInsight = this.getAttribute('data-ai-aiInsight'); // ボタンのAIインサイト

            // URLへ動的に情報を追加
            window.location.href = `/measures/create/${questionId}?aiInsight=${encodeURIComponent(aiInsight)}`;
        });

        document.getElementById('updateMeasureBtn').addEventListener('click', function() {
            // ボタンからデータ属性を取得
            const questionId = this.getAttribute('data-question-id'); // ボタンの質問ID
            const measureId = this.getAttribute('data-measure-id'); // ボタンの質問ID
            const aiInsight = this.getAttribute('data-ai-aiInsight'); // ボタンのAIインサイト

            // URLへ動的に情報を追加
            window.location.href = `/measures/create/${questionId}/?measureId=${encodeURIComponent(measureId)}&aiInsight=${encodeURIComponent(aiInsight)}`;
        });
    </script>
@endsection
