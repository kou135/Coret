<!-- resources/views/auth/company-register-step3.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認画面 - Coret</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen pt-3">
    <div class="w-5/6 max-w-full mx-auto">
        <div class="bg-box rounded-lg shadow-md w-full p-16 mt-10 mb-10">
            <x-regist-header>企業情報登録</x-regist-header>
            <x-progress-bar
            :steps="['企業情報', '組織構造', '入力内容確認', '登録完了']"
            :current="3"
            />

            <h3 class="font-noto text-center text-title text-lg mb-6 mt-10">こちらの情報でお間違いないですか？</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 justify-center">
                <div class="bg-white border border-bar rounded-md p-6 relative max-w-[420px] w-full mx-auto min-h-[450px]">

                    <div class="flex justify-between items-center mb-5">
                        <h4 class="text-lg font-semibold text-title font-noto">企業情報</h4>
                        <a href="{{ route('company.register.step1') }}" class="flex items-center text-bar text-xs border border-bar rounded-2xl px-4 py-1">
                            <img src="{{ asset('img/pen.svg') }}" alt="編集" class="w-3.5 h-3.5 mr-1.5">
                            編集
                        </a>
                    </div>

                    <div class="space-y-5">
                        <div class="flex justify-between text-base font-medium text-title px-4">
                            <span class="text-placeholder">会社名</span>
                            <span>{{ $step1Data['company_name'] }}</span>
                        </div>
                        <div class="flex justify-between text-base font-medium text-title px-4">
                            <span class="text-placeholder">従業員数</span>
                            <span>{{ $step1Data['employee_count'] }}</span>
                        </div>
                        <div class="flex justify-between text-base font-medium text-title px-4">
                            <span class="text-placeholder">事業年数</span>
                            <span>{{ $step1Data['years_in_business'] }}</span>
                        </div>
                        <div class="flex justify-between text-base font-medium text-title px-4">
                            <span class="text-placeholder">評価頻度</span>
                            <span>{{ $step1Data['evaluation_frequency'] }}</span>
                        </div>
                        <div class="flex justify-between text-base font-medium text-title px-4">
                            <span class="text-placeholder">給与の透明性</span>
                            <span>{{ $step1Data['salary_transparency'] }}</span>
                        </div>
                        <div class="flex justify-between text-base font-medium text-title px-4">
                            <span class="text-placeholder">評価制度</span>
                            <span>{{ $step1Data['evaluation_type'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-bar rounded-md p-6 relative max-w-[420px] w-full mx-auto">

                    <div class="flex justify-between items-center mb-5">
                        <h4 class="text-lg font-semibold text-title font-noto">組織構造</h4>
                        <a href="{{ route('company.register.step2') }}" class="flex items-center text-bar text-xs border border-bar rounded-2xl px-4 py-1">
                            <img src="{{ asset('img/pen.svg') }}" alt="編集" class="w-3.5 h-3.5 mr-1.5">
                            編集
                        </a>
                    </div>

                    @if(session('company_registration.step2.organizations'))
                    @php
                        $hierarchies = collect(session('company_registration.step2.organizations'))->pluck('hierarchy')->filter()->unique()->values();
                    @endphp

                    <div class="overflow-x-auto">
                        <div class="min-w-max">
                            <div class="inline-flex space-x-3.1r text-center mb-6 px-16">
                                @foreach ($hierarchies as $index => $level)
                                    <div class="whitespace-nowrap">
                                        <div class="text-sm text-placeholder mb-1">階層{{ $index + 1 }}</div>
                                        <div class="text-sm text-title">{{ $level }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="org-tree-container max-h-[400px] overflow-y-auto">
                                <div class="org-tree text-title space-y-4">
                                    {!! $orgTreeHtml !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif
                </div>

            </div>

            <form action="{{ route('company.register.step3.submit') }}" method="POST" class="text-center">
                @csrf
                <x-next-button>登録する</x-next-button>
            </form>
        </div>
    </div>
</body>
</html>
