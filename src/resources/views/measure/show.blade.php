@extends('layouts.app-layout')

@section('title', '施策詳細')

@section('content')
    <div class="px-8 pt-8">
        <div class="flex">
            <div class="flex items-center"><img src="{{ asset('img/checkTitle.svg') }}" alt=""></div>
            <div class="text-gray text-2xl font-bold pl-4">タスクが完了したらチェックしましょう</div>
        </div>
        <div class="container flex mt-4">
            <div class="w-1/2 flex border-2 border-lightGreen rounded-sm h-[61px]">
                <div class="text-lightGray text-md pl-6 flex items-center">施策名:</div>
                <div class="text-gray text-xl pl-4 flex items-center">{{ $measure->measure_title }}</div>
            </div>
            <div class="w-1/2 flex pl-6">
                <div class="flex items-center mr-2"><img src="{{ asset('img/CoretBot.svg') }}" alt=""></div>
                <div
                    class="text-white font-bold bg-lightGreen flex items-center px-4 rounded-tr-lg rounded-br-lg rounded-bl-lg rounded-tl-none">
                    現在の進捗:
                    {{ $completedTasksCount }}/{{ $totalTasks }}　タスク完了!　あと{{ $remainingTasks }}つで施策が完了します！</div>
            </div>
        </div>
        <div class="container flex gap-4 mt-4">
            <div class="w-[27%] h-[221px] border-2 border-lightGreen rounded-sm">
                <div class="px-6 py-4">
                    <div class="text-gray mb-5 text-xl font-bold">基本情報</div>
                    <div>
                        <div class="flex mb-1">
                            <div>項目:</div>
                            <div class="text-gray pl-3">{{ $measure->question->question_text }}</div>
                        </div>
                        <div class="flex mb-1">
                            <div>対象範囲:</div>
                            <div class="text-gray pl-3">{{ $measure->target_scope }}</div>
                        </div>
                        <div class="flex mb-1">
                            <div>実施期間:</div>
                            <div class="text-gray pl-3">{{ $measure->start_date }} - {{ $measure->end_date }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-[37%] h-[221px] border-2 border-lightGreen rounded-sm">
                <div class="px-6 py-4">
                    <div class="text-gray mb-5 text-xl font-bold">施策内容</div>
                    <div>{{ $measure->measure_description }}</div>
                </div>
            </div>
            @if ($measureEffect)
                <div class="w-[27%] h-[221px] border-2 border-lightGreen rounded-sm">
                    <div class="px-6 py-4">
                        <div class="text-gray mb-5 text-xl font-bold">施策効果</div>
                        <div>
                            <div class="text-gray mb-1">施策前スコア:　　{{ $measureEffect['before'] ?? 'データなし' }}</div>
                            <div class="text-gray mb-1">施策後スコア:　　{{ $measureEffect['after'] ?? 'データなし' }}</div>
                        </div>
                        @if (is_numeric($measureEffect['score_diff_text']))
                            @if ($measureEffect['after'] > $measureEffect['before'])
                                <div class="mt-3 py-4 bg-lightGreen rounded-md flex items-center justify-center text-white">
                                    スコアが{{ $measureEffect['score_diff_text'] }}点上昇</div>
                            @elseif ($measureEffect['after'] < $measureEffect['before'])
                                <div class="mt-3 py-4 bg-lightGreen rounded-md flex items-center justify-center text-white">
                                    スコアが{{ $measureEffect['score_diff_text'] }}点下降</div>
                            @endif
                        @else
                            <div class="text-gray mt-3">{{ $measureEffect['score_diff_text'] }}</div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="container pr-6">
            <div class="container mt-4 min-h-[480px] border-2 border-lightGreen rounded-sm">
                <div class="px-6 py-4">
                    <div class="text-gray mb-5 text-xl font-bold">タスク一覧</div>
                    <div class="flex justify-between">
                        <div class="w-[48%]">
                            {{-- 未完了タスク --}}
                            @php
                                $incompleteTasks = $measure->tasks->where('status', '未完了');
                            @endphp

                            <div
                                class="h-[40px] bg-lightGreen text-white font-bold flex items-center justify-center rounded-lg">
                                未完了　({{ $incompleteTasks->count() }})</div>
                            @foreach ($incompleteTasks as $task)
                                <div class="my-3 h-[80px] border-2 border-lightGray rounded-sm">
                                    <div class="h-full border-l-8 border-lightGreen flex items-center">
                                        <div class="pl-8">
                                            <form
                                                action="{{ route('measures.update', ['id' => $measure->id, 'taskId' => $task->id]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"><img src="{{ asset('img/UncompleatedCheck.svg') }}"
                                                        alt=""></button>
                                            </form>
                                        </div>
                                        <div>
                                            <div class="text-gray pl-6 text-lg">{{ $task->task_text }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if ($incompleteTasks->count() === 0)
                                <div class="mt-3 py-6 w-full bg-feet border-2 border-lightGreen rounded-sm">
                                    <div class="rounded-sm flex justify-center items-center">
                                    <img src="{{ asset('img/feet.svg') }}" alt="">
                                </div>
                                </div>
                            @endif
                        </div>
                        <div class="w-[48%]">
                            {{-- 完了タスク --}}
                            @php
                                $completedTasks = $measure->tasks->where('status', '完了');
                            @endphp

                            <div
                                class="h-[40px] bg-uncompleated text-white font-bold flex items-center justify-center rounded-lg">
                                完了　({{ $completedTasks->count() }})</div>
                            @foreach ($completedTasks as $task)
                                <div class="my-3 h-[80px] border-2 border-lightGray rounded-sm">
                                    <div class="h-full border-l-8 border-uncompleatedTask flex items-center">
                                        <div class="pl-8">
                                            <div><img src="{{ asset('img/CompleatedCheck.svg') }}" alt=""></div>
                                        </div>
                                        <div>
                                            <div class="text-gray pl-6 text-lg">{{ $task->task_text }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
