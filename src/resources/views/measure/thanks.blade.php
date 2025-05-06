@extends('layouts.app-layout')

@section('title', '施策完了')

@section('content')

    <body class="fixed w-full">
        <div class="bg-[#5ECCC7] p-8 h-screen flex justify-center items-center">
            <div class="w-[80%] max-w-5xl bg-white rounded-xl p-16 shadow-lg flex flex-col items-center mb-8">
                <div class="w-16 h-16 rounded-full border-4 border-[#5ECCC7] flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#5ECCC7]" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-medium text-gray-700 mb-6">施策が立案できました！</h2>

                <p class="text-gray-600 text-center mb-12">
                    タスクを終わらせて
                    <br />
                    施策を期限までに完了させましょう。
                </p>

                <div class="w-[60%] flex justify-center">
                    <div class="flex gap-4 w-full">
                        <a href="{{ route('home')  }}"
                            class="w-[48%] flex-1 border border-[#5ECCC7] text-[#5ECCC7] p-6 rounded flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                            ホームに戻る
                        </a>
                        <a href="{{ route('measures.index')  }}"
                            class="w-[48%] flex-1 bg-[#5ECCC7] text-white p-6 rounded flex items-center justify-center">
                            施策の一覧画面へ
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-5 ml-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection
