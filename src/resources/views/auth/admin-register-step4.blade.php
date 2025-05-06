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
        <div class="bg-box rounded-lg shadow-md w-full pt-10 pb-20 mt-10 mb-10">
            <x-regist-header>企業情報登録</x-regist-header>
            <x-progress-bar
            :steps="['企業コード', '情報登録', '入力内容確認', '登録完了']"
            :current="4"
            />

            <div class="text-center mb-16 mt-16">
                <div class="mb-10">
                    <img src="{{ asset('img/check.svg') }}" alt="完了" class="w-20 h-20 mx-auto">
                </div>
                <h2 class="text-xl font-medium text-title mb-6 font-noto">管理者登録が完了しました。</h2>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}">
                    <x-next-button>ログインへ</x-next-button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
