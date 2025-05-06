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
                :current="1"
            />

            <form action="{{ route('company.register.step1.store') }}" method="POST" class="max-w-xl mx-auto">
                @csrf

                @php
                    $fieldWrapper = 'flex flex-col space-y-1 w-full';
                    $inputStyle = 'w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue text-title bg-white';
                @endphp

                <div class="space-y-8 mt-14">
                    <h3 class="text-center text-title text-lg mb-6 font-noto">企業の基本情報を教えてください。</h3>

                    @foreach([
                        [
                            'type' => 'text',
                            'label' => '会社名',
                            'name' => 'company_name',
                            'placeholder' => '例）株式会社Coret',
                            'required' => true
                        ],
                        [
                            'type' => 'select',
                            'label' => '従業員数',
                            'name' => 'employee_count',
                            'options' => ['1〜10人','11〜50人','51〜100人','101〜300人','301〜500人','501〜1000人','1001人以上'],
                            'required' => false
                        ],
                        [
                            'type' => 'select',
                            'label' => '事業年数',
                            'name' => 'years_in_business',
                            'options' => ['1年未満','1〜3年','4〜10年','11〜20年','21〜50年','51年以上'],
                            'required' => false
                        ],
                    ] as $field)
                        <div class="{{ $fieldWrapper }}">
                            <label for="{{ $field['name'] }}" class="text-title font-noto text-sm">{{ $field['label'] }}</label>

                            @if ($field['type'] === 'text')
                                <input
                                    type="text"
                                    id="{{ $field['name'] }}"
                                    name="{{ $field['name'] }}"
                                    value="{{ old($field['name'], session("company_registration.step1.{$field['name']}")) }}"
                                    class="{{ $inputStyle }} placeholder-gray-400"
                                    placeholder="{{ $field['placeholder'] }}"
                                    {{ $field['required'] ? 'required' : '' }}>
                            @elseif ($field['type'] === 'select')
                                <select
                                    id="{{ $field['name'] }}"
                                    name="{{ $field['name'] }}"
                                    class="{{ $inputStyle }} text-placeholder"
                                    onchange="this.classList.remove('text-placeholder'); this.classList.add('text-title')"
                                    {{ $field['required'] ? 'required' : '' }}>
                                    <option value="" {{ !$field['required'] ? '' : 'disabled selected' }}>選択してください</option>
                                    @foreach ($field['options'] as $option)
                                        <option value="{{ $option }}" {{ old($field['name'], session("company_registration.step1.{$field['name']}")) === $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endforeach
                </div>

                <h3 class="font-noto text-center text-title text-lg mb-6 pt-14">企業の内部制度について教えてください。</h3>

                <div class="space-y-8">
                    @foreach([
                        [
                            'label' => '昇給・評価の頻度',
                            'name' => 'evaluation_frequency',
                            'options' => ['毎月','四半期ごと','半年ごと','年1回','不定期'],
                        ],
                        [
                            'label' => '給与体系の透明性',
                            'name' => 'salary_transparency',
                            'options' => ['全社公開','部署内公開','職種・役職ごとに公開','評価基準公開','非公開'],
                        ],
                        [
                            'label' => '評価制度の種類',
                            'name' => 'evaluation_type',
                            'options' => ['目標達成型（OKRやMBOなど）','上司による一方的評価','多面評価（360度評価など）','バリュー・行動指針ベース','能力やスキルに応じた評価'],
                        ],
                    ] as $field)
                        <div class="{{ $fieldWrapper }}">
                            <label for="{{ $field['name'] }}" class="text-title font-noto text-sm">{{ $field['label'] }}</label>
                            <select
                                id="{{ $field['name'] }}"
                                name="{{ $field['name'] }}"
                                class="{{ $inputStyle }} text-placeholder"
                                onchange="this.classList.remove('text-placeholder'); this.classList.add('text-title')">
                                <option value="">選択してください</option>
                                @foreach ($field['options'] as $option)
                                    <option value="{{ $option }}" {{ old($field['name'], session("company_registration.step1.{$field['name']}")) === $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            <x-next-button>次へ</x-next-button>
            </form>
        </div>
    </div>
</body>
</html>
