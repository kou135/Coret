@extends('layouts.app-layout')

@section('title', '施策立案')

@section('content')

    <body class="fixed w-full">
        <div class="flex h-screen">
            <div class="container h-screen">
                <div class="flex h-full">
                    <!-- 左：チャット部分 -->
                    <div class="flex flex-col w-[58%] border-r border-lightGray relative h-[calc(100vh-107px)]">
                        <div class="h-[58px] bg-lightGreen text-white flex items-center justify-center">
                            <div class="text-[16px]">解決したい課題:　</div>
                            <div class="text-white text-[16px]">{{ $questionText }}</div>
                        </div>
                        <!-- チャットコンテンツエリア - スクロールなし、はみ出た部分は非表示 -->
                        <div class="flex-1 p-3 overflow-y-auto mb-16">
                            {{-- 以下divで囲った範囲はスクロール可能範囲 --}}
                            <div class="overflow-auto">
                                <!-- AIアイコンとメッセージ -->
                                <div class="flex items-start gap-1 mb-4">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white pt-1">
                                        <svg viewBox="0 0 24 24" width="28" height="24" viewBox="0 0 24 18"
                                            fill="none">
                                            <path
                                                d="M7 1.2916C7 1.94867 5.4 4.20149 3.5 6.17271C1.6 8.14392 0 10.209 0 10.8661C0 11.5231 1.6 13.3066 3.5 14.9024C8.6 19.0325 15.4 19.0325 20.5 14.9024C22.4 13.3066 24 11.5231 24 10.8661C24 10.209 22.4 8.14392 20.5 6.17271C18.6 4.20149 17 1.94867 17 1.2916C17 -0.491879 15.1 -0.398012 14.4 1.38547C14 2.13641 13 2.79348 12 2.79348C11 2.79348 10 2.13641 9.6 1.38547C8.9 -0.398012 7 -0.491879 7 1.2916ZM17.4 8.61326C19.1 11.2415 16.9 13.4944 12.6 13.8698C7 14.3392 3.9 10.7722 7.2 7.67459C9 5.98497 16.1 6.64204 17.4 8.61326Z"
                                                fill="#2BC7BC" />
                                            <path
                                                d="M8 10.303C8 10.7724 8.5 11.2417 9 11.2417C9.6 11.2417 10 10.7724 10 10.303C10 9.73982 9.6 9.36435 9 9.36435C8.5 9.36435 8 9.73982 8 10.303Z"
                                                fill="#2BC7BC" />
                                            <path
                                                d="M14 10.303C14 10.7724 14.5 11.2417 15 11.2417C15.6 11.2417 16 10.7724 16 10.303C16 9.73982 15.6 9.36435 15 9.36435C14.5 9.36435 14 9.73982 14 10.303Z"
                                                fill="#2BC7BC" />
                                        </svg>
                                    </div>
                                    <div class="p-4 bg-white border-[#5ECFBC] border rounded-xl rounded-tl-none">
                                        <p class="text-[#60666c]">
                                            Coret 専用AI chat です🔍<br>
                                            企業の課題に対して、最適な施策を提案いたします。
                                        </p>
                                    </div>
                                </div>
                                <!-- AIの回答 - Laravelの変数を使用 -->
                                <div class="message">
                                </div>
                                <!-- 前回施策が存在する場合、前回施策を表示 -->
                                @if (isset($previousAiProposal) && !empty($previousAiProposal))
                                    <div class="ml-8 mr-2 p-4 bg-white border-[#5ECFBC] border rounded-xl rounded-tl-none">
                                        <strong>前回施策提案</strong>
                                        <div><strong>施策名：</strong> {{ $previousAiProposal['lastTitle'] ?? 'データがありません' }}
                                        </div>
                                        <div><strong>見えてきた改善点：</strong> {!! nl2br(e($previousAiProposal['hypothesis'] ?? 'データがありません')) !!}</div>
                                        <div><strong>今後の方向性：</strong> {!! nl2br(e($previousAiProposal['policys'] ?? 'データがありません')) !!}</div>
                                    </div>
                                    <!-- 前回施策が存在しない場合、今回施策を表示 -->
                                @elseif(isset($aiProposal) && !empty($aiProposal))
                                    <div
                                        class="ml-9 mr-2 p-4 bg-white border-[#5ECFBC] border rounded-xl rounded-tl-none text-[#60666c]">
                                        <strong>今回の施策提案</strong>
                                        <div class="p-1"><strong>■施策名：</strong> {{ $aiProposal['title'] ?? 'データがありません' }}
                                        </div>
                                        <div class="p-1"><strong>■施策概要：</strong>
                                            {{ $aiProposal['description'] ?? 'データがありません' }}</div>
                                        <div class="p-1"><strong>■施策期間：</strong>
                                            {{ $aiProposal['period'] ?? 'データがありません' }}</div>
                                        <div class="p-1"><strong>■タスク：</strong> {!! nl2br(e($aiProposal['tasks'] ?? 'データがありません')) !!}</div>
                                    </div>
                                @endif
                                <!-- チャットメッセージを表示するエリア -->
                                <div class="flex-1 p-4 overflow-auto" id="chatContainer">
                                    @if (!empty($chatHistory))
                                        @foreach ($chatHistory as $chat)
                                            <div class="w-full flex justify-end">
                                                <div
                                                    class="mr-2 p-4 mb-2 bg-[#5ECFBC] border-[#5ECFBC] border rounded-xl rounded-tr-none text-white flex justify-end max-w-max">
                                                    {{ $chat['user'] }}
                                                </div>
                                            </div>
                                            <div
                                                class="ml-7 mr-2 mb-2 p-4 bg-white border-[#5ECFBC] border rounded-xl rounded-tl-none text-[#60666c]">
                                                {{ $chat['ai'] }}
                                            </div>
                                        @endforeach
                                    @else
                                        {{-- <div class="mb-2 text-gray-500">まだチャットの履歴はありません。</div> --}}
                                    @endif
                                </div>
                                <!-- チャットメッセージを表示するエリア -->
                                <div class="flex-1 p-4 overflow-auto" id="chatContainer">
                                    <!-- 送信されたメッセージはここに表示されます -->
                                </div>
                            </div>
                            <!-- 入力フォーム -->
                            <div
                                class="p-4 border-t border-[#60666c] bottom-0 left-0 right-0 absolute bg-white border-opacity-30">
                                <form action="{{ route('measures.send', ['questionId' => $questionId]) }}" id="chatForm"
                                    method="POST" class="flex gap-2 w-[80%] mx-auto">
                                    @csrf
                                    <input type="text" name="message" id="userInput"
                                        class="flex-1 border rounded p-3 text-[16px]" placeholder="AIに質問してみましょう...">
                                    <button type="submit" id="sendButton"
                                        class="bg-[#5ECFBC] hover:bg-[#4ab3a2] text-white px-4 py-2 rounded text-[16px]">送信</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- 右：施策入力 -->
                    <div class="h-full bg-white rounded-lg shadow w-[42%]">
                        <div class="px-6 pt-8">
                            <div>
                                <div class="flex gap-2">
                                    <div><img src="{{ asset('img/Vector.svg') }}" alt="" class="w-6 h-auto"></div>
                                    <div class="text-[20px] text-[#60666c] font-bold">
                                        課題を解決するための施策をAIと考えましょう</div>
                                </div>
                                <div class="mt-2 text-gray text-[16px]">以下を埋めてください</div>
                            </div>
                            @if ($errors->any())
                                <div class="text-red-500">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="mt-8 w-full">
                                <form method="POST" action="{{ route('measures.store') }}">
                                    @csrf
                                    <input type="hidden" name="survey_id" value="{{ $surveyId }}">
                                    <input type="hidden" name="user_id" value="{{ $userId }}">
                                    <input type="hidden" name="company_id" value="{{ $companyId }}">
                                    <input type="hidden" name="organization_hierarchy_id"
                                        value="{{ $organizationHierarchyId }}">
                                    <input type="hidden" name="organization_names_id" value="{{ $organizationNamesId }}">
                                    <input type="hidden" name="question_id" value="{{ $questionId }}">
                                    <div class="mb-12 flex">
                                        <div class="w-1/3 items-center">
                                            <div class="flex justify-between pr-5 items-center">
                                                <div><label class="block text-[17px] font-medium text-[#60666c]">施策名</label>
                                                </div>
                                                <div
                                                    class="w-[60px] h-[30px] bg-lightGreen flex items-center justify-center rounded-full">
                                                    <div class="text-[14px] p-2 text-white">必須</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-2/3">
                                            <input type="text" name="measure_title"
                                                class="block w-full border rounded px-3 py-2 text-[15px] h-[35px] "
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-4 flex">
                                        <div class="w-1/3 items-center">
                                            <div class="flex justify-between pr-5 items-center">
                                                <div><label
                                                        class="block text-[17px] font-medium text-[#60666c]">施策内容</label>
                                                </div>
                                                <div
                                                    class="w-[60px] h-[30px] bg-lightGreen flex items-center justify-center rounded-full">
                                                    <div class="text-[14px] p-2 text-white">必須</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-2/3">
                                            <textarea name="measure_description" rows="4" class="block w-full border rounded px-3 py-2 text-[15px] h-[70px]"
                                                required></textarea>
                                        </div>
                                    </div>
                                    <div class="flex mb-4">
                                        <div class="w-1/3 items-center">
                                            <div class="flex justify-between pr-5 items-center">
                                                <div class="flex">
                                                    <label class="block text-[17px] font-medium text-[#60666c]">タスク</label>
                                                </div>
                                                <div
                                                    class="w-[60px] h-[30px] bg-lightGreen flex items-center justify-center rounded-full">
                                                    <div class="text-[14px] p-2 text-white">必須</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-2/3">
                                            <div class="overflow-y-auto h-[270px] rounded mt-1">
                                                <!-- タスクをラップするコンテナ -->
                                                <div id="taskContainer">
                                                    @for ($i = 1; $i <= 10; $i++)
                                                        <div class="task-entry mb-5">
                                                            <div class="items-center gap-2 mb-2">
                                                                <div class="flex items-center gap-3">
                                                                    <p class="text-[18px] text-[#60666c]">
                                                                        {{ $i }},</p>
                                                                    <input type="text"
                                                                        name="tasks[{{ $i }}][task_text]"
                                                                        class="flex-1 border rounded px-3 py-2 mb-2"
                                                                        placeholder="タスクを入力"
                                                                        value="{{ old('tasks.' . $i . '.task_text') }}">
                                                                </div>
                                                                <div class="flex">
                                                                    <!-- 日付設定ラベル -->
                                                                    <label for="task_{{ $i }}_date"
                                                                        class="text-[14px] text-[#60666c] cursor-pointer">日付を設定</label>
                                                                    <!-- 日付入力フィールド -->
                                                                    <input type="date"
                                                                        id="task_{{ $i }}_date"
                                                                        name="tasks[{{ $i }}][deadline_date]"
                                                                        class="task-date border-none bg-white text-[14px] h-7 text-[#60666c] w-[140px] pr-3 focus:ring-0"
                                                                        style="color: #60666c;"
                                                                        value="{{ old('tasks.' . $i . '.deadline_date') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 flex">
                                        <div class="w-1/3 items-center">
                                            <div class="flex justify-between pr-5 items-center">
                                                <div><label
                                                        class="block text-[17px] font-medium text-[#60666c]">実施期間</label>
                                                </div>
                                                <div
                                                    class="w-[60px] h-[30px] bg-[#60666c] flex items-center justify-center rounded-full">
                                                    <div class="text-[14px] p-2 text-white">任意</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-2/3">
                                            <div class="flex gap-2 items-center">
                                                <div class="w-1/2">
                                                    <label class="block text-[15px] text-[#60666c]  mb-1">開始日</label>
                                                    <input type="date" name="start_date"
                                                        class="w-full border rounded px-3 py-2 text-[#60666c] text-[15px] h-[35px]">
                                                </div>
                                                <div class="w-1/2">
                                                    <label class="block text-[15px] text-[#60666c] mb-1">終了日</label>
                                                    <input type="date" name="end_date"
                                                        class="w-full border rounded px-3 py-2 text-[#60666c] text-[15px] h-[35px]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 flex">
                                        <div class="w-1/3 items-center">
                                            <div class="flex justify-between pr-5 items-center">
                                                <div><label
                                                        class="block text-[17px] font-medium text-[#60666c]">対象範囲</label>
                                                </div>
                                                <div
                                                    class="w-[60px] h-[30px] bg-[#60666c] flex items-center justify-center rounded-full">
                                                    <div class="text-[14px] p-2 text-white">任意</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-2/3">
                                            <input type="text" name="target_scope"
                                                class="block w-full border rounded px-3 py-2 text-[15px] h-[35px]">
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-center">
                                        <button type="submit"
                                            class=" w-[180px] bg-lightGreen text-white px-2 py-5 rounded-lg text-[15px]">この施策で実行する
                                            ></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    </body>


    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     let taskIndex = 1; // タスクインデックスを初期化
        //     console.log('Initial taskIndex:', taskIndex); // 初期値のインデックスを確認


        //     document.getElementById('addTaskBtn').addEventListener('click', function() {
        //         console.log('クリックされました');
        //         // タスクリストを取得
        //         const taskList = document.getElementById('taskList');
        //         console.log(taskList); // taskListが正しく取得されているか確認

        //         // 新しいタスクフォームを作成
        //         const newTask = document.createElement('div');
        //         newTask.classList.add('flex', 'items-center', 'gap-2', 'mb-2', 'task-entry');

        //         // 動的にインデックスを使ってタスク名と日付を追加
        //         newTask.innerHTML = `
    //             <input type="text" name="tasks[${taskIndex}][task_text]"
    //                     class="flex-1 border rounded px-3 py-2" placeholder="タスクを入力">
    //                 <div class="flex flex-col">
    //                     <div class="text-xs text-lightGray">日付を設定</div>
    //                     <input type="date" name="tasks[${taskIndex}][deadline_date]"
    //                         class="border rounded text-xs h-7 text-[#60666c]">
    //                 </div>
    //         `;

        //         // 新しいタスクフォームをタスクリストに追加
        //         taskList.appendChild(newTask);

        //         // 次のタスクのインデックスを増加
        //         taskIndex++;
        //     });
        // });

        document.getElementById('chatForm').addEventListener('submit', function(event) {
            event.preventDefault(); // フォームのデフォルト送信を防ぐ

            const message = document.getElementById('userInput').value;
            const questionId = {{ $questionId }}; // 質問IDを渡す
            const aiInsight = "{{ $aiInsight }}"; // AIの推論結果

            // チャットボックスにユーザーのメッセージを追加
            const chatBox = document.getElementById('chatContainer');
            chatBox.innerHTML +=
                <
                div class = "bg-teal-100 p-2 text-sm text-gray-700 mb-2" >
                <
                strong > あなた: < /strong> ${message} < /
            div > ;

            // チャットボックスをスクロールして、最新メッセージを表示
            chatBox.scrollTop = chatBox.scrollHeight;

            // AIにリクエストを送信
            fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        message: message,
                        questionId: questionId,
                        aiInsight: aiInsight,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    // AIのレスポンスをチャットボックスに追加
                    chatBox.innerHTML +=
                        <
                        div class = "bg-gray-100 rounded p-2 text-sm text-gray-700 mb-2" >
                        <
                        strong > AI: < /strong> ${data.response} < /
                    div > ;

                    // チャットボックスをスクロールして、最新メッセージを表示
                    chatBox.scrollTop = chatBox.scrollHeight;

                    // 入力フィールドをクリア
                    document.getElementById('userInput').value = '';
                });
        });
    </script>
@endsection
