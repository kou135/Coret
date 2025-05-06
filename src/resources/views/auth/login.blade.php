<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - Coret</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white min-h-screen flex items-center justify-center">
    <div class="bg-box rounded-lg shadow-lg w-full max-w-md p-8">
        <x-regist-header>ログイン</x-regist-header>


        <!-- バリデーションエラー -->
        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ログインフォーム -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-roboto text-title mb-1">メールアドレス</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full border rounded px-3 py-2 placeholder-placeholder text-title" placeholder="taro@example.com">
            </div>

            <div class="pb-6">
                <div class="mb-2">
                    <label for="password" class="block text-sm font-roboto text-title mb-1">パスワード</label>
                    <input type="password" id="password" name="password" required class="w-full border rounded px-3 py-2 placeholder-placeholder text-title"
                        placeholder="パスワードを入力">
                </div>

            <div class="mt-8">
                <div class="flex justify-center">
                    <button type="submit" class="bg-bar text-white px-10 py-3 mb-2 rounded hover:bg-hoverBar transition">
                        ログイン
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('admin.register.step1') }}" class="text-sm text-bar hover:underline font-noto">
                        新規登録の方はこちら
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
