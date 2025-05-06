<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者情報登録 - Coret</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white min-h-screen pt-3">
    <div class="w-5/6 max-w-full mx-auto">
        <div class="bg-box rounded-lg shadow-md w-full pt-10 pb-16 mt-10 mb-10">
            <x-regist-header>企業情報登録</x-regist-header>
            <x-progress-bar :steps="['企業コード', '情報登録', '入力内容確認', '登録完了']" :current="2" />

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.register.step2.store') }}">
                @csrf
                <div class="grid grid-cols-[1fr_1px_1fr] gap-28 mt-16 px-36">
                    <div>
                        <h3 class="font-roboto text-xl font-semibold text-title mb-2">基本情報</h3>
                        <h3 class="font-noto text-title text-base">あなたの個人情報を教えてください。</h3>

                        <div class="flex gap-4 mt-6">
                            <div class="mb-4">
                                <label class="block text-sm text-title mb-1">苗字</label>
                                <input type="text" name="last_name" placeholder="例：山田" value="{{ old('last_name', $data['last_name'] ?? '') }}"
                                    class="w-full border rounded px-3 py-2 placeholder-placeholder text-title" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm text-title mb-1">名前</label>
                                <input type="text" name="first_name" placeholder="例：太郎"
                                    value="{{ old('first_name', $data['first_name'] ?? '') }}"
                                    class="w-full border rounded px-3 py-2 placeholder-placeholder text-title" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-title mb-1">メールアドレス</label>
                            <input type="email" name="email" placeholder="例：taro@example.com"
                                value="{{ old('email', $data['email'] ?? '') }}"
                                class="w-full border rounded px-3 py-2 placeholder-placeholder text-title" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-title mb-1">パスワード</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" placeholder="パスワードを入力"
                                    class="w-full border rounded px-3 py-2 placeholder-placeholder text-title" required>
                                <button type="button"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none toggle-password"
                                    data-target="password">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-off-icon hidden"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                        </path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-title mb-1">パスワード確認</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="パスワードを再入力"
                                    class="w-full border rounded px-3 py-2 placeholder-placeholder text-title" required>
                                <button type="button"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none toggle-password"
                                    data-target="password_confirmation">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-off-icon hidden"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                        </path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-title mb-1">役職</label>
                            <input type="text" name="position" value="{{ old('position', $data['position'] ?? '') }}"
                                class="w-full border rounded px-3 py-2 placeholder-placeholder text-title" placeholder="例：部長">
                        </div>

                        @foreach ($hierarchies as $hierarchy)
                            <div class="mb-4">
                                <label class="block text-sm text-title mb-1">{{ $hierarchy->name }}</label>
                                <select name="organizations[{{ $hierarchy->id }}]"
                                    class="js-select w-full border rounded px-3 py-2 text-placeholder">
                                    <option value="">選択してください</option>
                                    @foreach ($hierarchy->organizationNames as $org)
                                        <option value="{{ $org->id }}"
                                            {{ old("organizations.{$hierarchy->id}", $data['organizations'][$hierarchy->id] ?? '') == $org->id ? 'selected' : '' }}>
                                            {{ $org->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-title w-px h-auto mx-auto"></div>

                    <div>
                        <h3 class="text-xl font-semibold text-title mb-2">組織情報</h3>
                        <h3 class="font-noto text-title text-base ">あなたの組織について教えてください。</h3>

                        <div class="mb-4 mt-6">
                            <label class="block text-sm text-title mb-1">業界</label>
                            <div class="relative">
                                @php $selectedIndustries = old('industry', $data['industry'] ?? []); @endphp
                                <div id="selected-tags" class="flex flex-wrap min-h-[42px] w-full p-2 border border-title rounded bg-white cursor-pointer"></div>
                                <button type="button" id="dropdown-toggle" class="absolute right-2 top-2">
                                    <img src="{{ asset('img/choiceArrow.svg') }}" class="w-5 h-5" />
                                </button>
                                <div id="dropdown-menu" class="hidden absolute top-full left-0 w-5/6 max-h-52 overflow-y-auto bg-white border border-gray-300 rounded-md z-10 shadow-md">
                                    @foreach ([
                                        'IT・通信', '製造', '小売・流通', '金融', '教育・学習支援', 'サービス', '医療・福祉',
                                        'ソフトウェア', '広告・出版・マスコミ', '建築・不動産', '官公庁・公社・団体', 'その他'
                                    ] as $industry)
                                        <div class="px-4 py-2 hover:bg-gray-100">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="industry[]" value="{{ $industry }}" class="mr-2 industry-checkbox text-bar focus:ring-bar"
                                                    {{ in_array($industry, $selectedIndustries) ? 'checked' : '' }}>
                                                {{ $industry }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-title mb-1">組織の人数</label>
                            <input type="text" name="organization_size" value="{{ old('organization_size', $data['organization_size'] ?? '') }}"
                                class="w-full border rounded px-3 py-2 placeholder-placeholder" placeholder="例：20人">
                        </div>

                        @php
                            $selectFields = [
                                'remote_work_status' => 'リモートワークの可否',
                                'flex_time_status' => 'フレックスタイムの運用有無',
                                'one_on_one_frequency' => '1on1の頻度',
                                'age_distribution' => '年齢層',
                                'average_overtime_hours' => '平均残業時間',
                            ];

                            $options = [
                                'remote_work_status' => ['フルリモート可', 'ハイブリッド型', '限定的に可能', 'オフィス勤務のみ'],
                                'flex_time_status' => ['完全フレックス', 'コアタイムあり', '一部職種のみ', '導入なし'],
                                'one_on_one_frequency' => ['実施していない', '年に1回程度', '半年に1回程度', '月に1回程度', '毎週 or 隔週で実施している'],
                                'age_distribution' => ['20代が中心', '30代が中心', '40代以上が中心', '幅広い年齢層が在籍'],
                                'average_overtime_hours' => ['ほぼ残業なし', '月10時間程度', '月20時間程度', '月30時間程度', '月40時間以上'],
                            ];
                        @endphp

                        @foreach ($selectFields as $field => $label)
                            <div class="mb-4">
                                <label class="block text-sm text-title mb-1">{{ $label }}</label>
                                <select name="{{ $field }}" class="js-select w-full border rounded px-3 py-2 text-placeholder">
                                    <option value="">選択してください</option>
                                    @foreach ($options[$field] as $option)
                                        <option value="{{ $option }}" {{ old($field, $data[$field] ?? '') == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="text-center mt-10">
                    <x-next-button>次へ</x-next-button>
                </div>
            </form>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dropdownToggle = document.getElementById('dropdown-toggle');
                const dropdownMenu = document.getElementById('dropdown-menu');
                const selectedTags = document.getElementById('selected-tags');
                const checkboxes = document.querySelectorAll('.industry-checkbox');

                function updateSelectedTags() {
                    selectedTags.innerHTML = '';
                    let hasSelection = false;

                    checkboxes.forEach(checkbox => {
                        if (checkbox.checked) {
                            hasSelection = true;
                            const tag = document.createElement('div');
                            tag.className =
                                'inline-flex items-center bg-gray-100 rounded-full px-3 py-1 m-1 text-sm';
                            tag.innerHTML =
                                `${checkbox.value}<span class="ml-2 cursor-pointer" data-value="${checkbox.value}">×</span>`;
                            selectedTags.appendChild(tag);
                        }
                    });

                    if (!hasSelection) {
                        selectedTags.innerHTML = '<span class="text-placeholder">業界を選択してください</span>';
                    }

                    selectedTags.querySelectorAll('span[data-value]').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const value = this.getAttribute('data-value');
                            const target = document.querySelector(
                                `.industry-checkbox[value='${value}']`);
                            if (target) target.checked = false;
                            updateSelectedTags();
                        });
                    });
                }

                dropdownToggle.addEventListener('click', () => {
                    dropdownMenu.classList.toggle('hidden');
                });

                selectedTags.addEventListener('click', () => {
                    dropdownMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', e => {
                    if (
                        !dropdownToggle.closest('.relative').contains(e.target) &&
                        !selectedTags.contains(e.target)
                    ) {
                        dropdownMenu.classList.add('hidden');
                    }
                });

                checkboxes.forEach(cb => cb.addEventListener('change', updateSelectedTags));
                updateSelectedTags();

                const selects = document.querySelectorAll('.js-select');
                selects.forEach(select => {
                    const updateStyle = () => {
                        if (select.value === '') {
                            select.classList.add('text-placeholder');
                            select.classList.remove('text-title');
                        } else {
                            select.classList.remove('text-placeholder');
                            select.classList.add('text-title');
                        }
                    };
                    select.addEventListener('change', updateStyle);
                    updateStyle();
                });

                document.querySelectorAll('.toggle-password').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const passwordInput = document.getElementById(targetId);
                        const eyeIcon = this.querySelector('.eye-icon');
                        const eyeOffIcon = this.querySelector('.eye-off-icon');

                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            eyeIcon.classList.add('hidden');
                            eyeOffIcon.classList.remove('hidden');
                        } else {
                            passwordInput.type = 'password';
                            eyeIcon.classList.remove('hidden');
                            eyeOffIcon.classList.add('hidden');
                        }
                    });
                });
            });
        </script>
</body>

</html>
