@extends('layouts.app-layout')

@section('title', '施策一覧')

@section('content')
<div class="bg-white shadow-sm overflow-hidden mt-12 px-24">
    <div class="">
    <div class="border-t-4 border-lightGreen rounded-t-[50px]">
    <!-- テーブルヘッダー -->
    <div class="rounded-t-[50px] bg-teal-50 grid grid-cols-4 py-3 px-6 font-medium text-gray border-b">
        <div class="text-center">施策名</div>
        <div class="text-center">タスク達成度</div>
        <div class="text-center">実施期間</div>
        <div class="text-center">ステータス</div>
    </div>

    <!-- テーブル本体 -->
    <div>
    @foreach($measures as $measure)
    <div class="grid grid-cols-4 py-3 px-6 items-center {{ $loop->odd ? 'bg-white' : 'bg-[#EAF9F9]' }} ...">
        <div class="text-center text-gray">
            <a href="{{ route('measures.show', $measure['id']) }}" class="hover:underline">
                {{ $measure['measure_title'] }}
            </a>
        </div>
        <div class="text-center text-gray">
            {{-- 進捗率を「完了タスク数/全タスク数」の形式で表示 --}}
            {{ $measure['progress_rate'] }}
        </div>
        <div class="text-center text-gray">
            {{-- すでに 'date_range' に仕上げているなら --}}
            {{ $measure['date_range'] }}
        </div>
        <div class="text-center text-gray">
            {{ $measure['status'] }}
        </div>
    </div>
    @endforeach
    </div>
    </div>

    <!-- ページネーション -->
    <div class="px-6 py-4 flex justify-center">
        {{ $measures->links() }} 
        {{-- Laravel標準のページャUI --}}
    </div>
</div>
</div>
@endsection