<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>管理者登録 - Step1</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen pt-3">
    <div class="w-5/6 max-w-full mx-auto">
        <div class="bg-box rounded-lg shadow-md w-full pt-10 pb-28 mt-10 mb-10">
            <x-regist-header>企業情報登録</x-regist-header>
            <x-progress-bar
            :steps="['企業コード', '情報登録', '入力内容確認', '登録完了']"
            :current="1"
            />

        <form action="{{ route('admin.register.step1.store') }}" method="POST">
            @csrf
            <h3 class="font-noto text-center text-title text-lg mt-16">企業コードを入力してください。</h3>
            <div class="flex justify-center gap-4 my-12">
                @for ($i = 0; $i < 4; $i++)
                    <input type="text"
                        name="company_code_digit[]"
                        maxlength="1"
                        class="code-input w-12 h-12 text-xl text-center border rounded-md mb-8"
                        inputmode="numeric"
                        pattern="\d*"
                        autocomplete="off"
                        required>
                @endfor
            </div>

            @error('company_code')
                <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
            @enderror

            <input type="hidden" name="company_code" id="company_code">
            <x-next-button>次へ</x-next-button>
        </form>
    </div>

    <script>
        const form = document.querySelector('form');
        const hiddenInput = document.getElementById('company_code');

        document.querySelectorAll('.code-input').forEach((input, index, inputs) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        form.addEventListener('submit', (e) => {
            const digits = Array.from(document.querySelectorAll('[name="company_code_digit[]"]'))
                                .map(input => input.value.trim());
            hiddenInput.value = digits.join('');
        });
    </script>
    
</body>

</html>
