<div class="w-full h-[107px] bg-header border-b border-lightGray">
    <div class="w-full bg-haeder border-b border-lightGray px-6 py-5">
        <div class="flex items-center justify-between">
            <!-- 階層（部署） -->
            @if (!empty($departmentName))
                <div class="flex items-center space-x-5 text-xl text-gray font-bold pl-5">
                    @foreach (explode(' ', $departmentName) as $name)
                        <div>{{ $name }}</div>
                    @endforeach
                </div>
            @endif

            <!-- 氏名 -->
            <div class="text-sm text-gray pr-8">
                {{ $userName }}さん
            </div>
        </div>
    </div>
    <div class="items-center">
        <!-- ステップ表示 -->
        @if (isset($currentStep))
            <div class="flex items-center space-x-1 text-sm py-1 pl-6">
                @foreach (['step1 課題発見', 'step2 施策立案', 'step3 施策実行'] as $step)
                    <div class="text-sm font-semibold {{ $step === $currentStep ? 'text-bar' : 'text-placeholder' }}">
                        {{ strtoupper($step) }}
                    </div>
                    @if (!$loop->last)
                        <div class="text-placeholder px-5">>></div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>