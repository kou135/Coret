<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>企業情報登録 - Coret</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen pt-3">
    <div class="w-5/6 max-w-full mx-auto">
        <div class="bg-box rounded-lg shadow-md w-full p-10 mt-10 mb-10">
            <x-regist-header>企業情報登録</x-regist-header>
            <x-progress-bar
            :steps="['企業情報', '組織構造', '入力内容確認', '登録完了']"
            :current="4"
            />

            <div class="text-center mb-12 mt-10">
                <div class="mb-6">
                    <img src="{{ asset('img/check.svg') }}" alt="完了" class="w-20 h-20 mx-auto">
                </div>

                <h2 class="text-xl font-medium text-title mb-6 font-noto">企業情報の登録が完了しました。</h2>

                @if(session('company_code'))
                    <div class="bg-white text-title px-14 py-5 rounded-sm inline-block">
                        <p class="mb-1 text-26px font-medium">企業コードは
                            <span class="text-bar font-bold tracking-wider">{{ session('company_code') }}</span> です。
                        </p>
                        <p class="text-21px font-medium">管理者登録の際にこちらを入力してください。</p>
                    </div>
                @endif
            </div>

            <div class="text-center">
                <a href="{{ route('admin.register.step1') }}">
                    <x-next-button>管理者登録ページへ</x-next-button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
