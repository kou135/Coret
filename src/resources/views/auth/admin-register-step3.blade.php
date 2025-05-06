<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>入力内容確認 - Coret</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white min-h-screen pt-3">
    <div class="w-5/6 max-w-full mx-auto">
        <div class="bg-box rounded-lg shadow-md w-full py-10 px-24 mt-10 mb-10">
            <x-regist-header>企業情報登録</x-regist-header>
            <x-progress-bar :steps="['企業コード', '情報登録', '入力内容確認', '登録完了']" :current="3" />

            <h3 class="font-noto text-center text-title text-lg mb-8 mt-8">こちらの情報でお間違いないですか？</h3>

            <div class="bg-white border border-bar rounded-md px-6 pt-8 pb-16 relative">
                <a href="{{ route('admin.register.step2') }}"
                    class="absolute top-4 right-4 flex items-center text-bar text-xs border border-bar rounded-2xl px-4 py-1 hover:bg-bar/10 transition">
                    <img src="{{ asset('img/pen.svg') }}" alt="編集" class="w-3.5 h-3.5 mr-1.5">
                    編集
                </a>
                <div class="grid grid-cols-1 lg:grid-cols-2 divide-x divide-title">

                    <div class="pr-6">
                        <h4 class="text-lg font-semibold text-title font-noto mb-4">基本情報</h4>
                        <div class="space-y-5">
                            <div class="flex justify-between text-base font-medium text-title">
                                <span class="text-placeholder">氏名：</span>
                                <span>{{ $step2['last_name'] }} {{ $step2['first_name'] }}</span>
                            </div>
                            <div class="flex justify-between text-base font-medium text-title">
                                <span class="text-placeholder">メールアドレス：</span>
                                <span>{{ $step2['email'] }}</span>
                            </div>
                            <div class="flex justify-between text-base font-medium text-title">
                                <span class="text-placeholder">役職：</span>
                                <span>{{ $step2['position'] }}</span>
                            </div>
                            @foreach ($selectedOrganizations as $org)
                                <div class="flex justify-between text-base font-medium">
                                    <span class="text-placeholder">{{ $org->organizationHierarchy->name }}：</span>
                                    <span class="text-title">{{ $org->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pl-6">

                        <h4 class="text-lg font-semibold text-title font-noto mb-4">組織情報</h4>
                        <div class="space-y-5">
                            <div class="flex justify-between text-base font-medium text-title">
                                <span class="text-placeholder">業界：</span>
                                <span>{{ is_array($step2['industry'] ?? null) ? implode(', ', $step2['industry']) : '-' }}</span>
                            </div>
                            <div class="flex justify-between text-base font-medium text-title">
                                <span class="text-placeholder">組織の人数：</span>
                                <span>{{ $step2['organization_size'] ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-base font-medium text-title">
                                <span class="text-placeholder">1on1実施頻度：</span>
                                <span>{{ $step2['one_on_one_frequency'] ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-base font-medium text-title">
                                <span class="text-placeholder">リモートワーク：</span>
                                <span>{{ $step2['remote_work_status'] ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-base font-medium text-title">
                                <span class="text-placeholder">フレックス制度：</span>
                                <span>{{ $step2['flex_time_status'] ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-base font-medium text-title">
                                <span class="text-placeholder">平均残業時間：</span>
                                <span>{{ $step2['average_overtime_hours'] ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <form method="POST" action="{{ route('admin.register.step3.submit') }}">
                    @csrf
                    <x-next-button>登録する</x-next-button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
