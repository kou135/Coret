<div class="flex justify-center items-center my-4">
    @foreach ($steps as $index => $label)
        @php
            $stepNum = $index + 1;
            $isActive = $stepNum <= $current;
        @endphp

        <div class="flex flex-col items-center w-16"> <!-- 👈 幅を固定 -->
            <div class="
                w-9 h-9 rounded-full flex items-center justify-center mb-2 font-noto font-bold
                {{ $isActive ? 'bg-bar text-white' : 'border-3 border-bar text-bar' }}
            ">
                {{ $stepNum }}
            </div>
            <span class="text-xs text-title text-center whitespace-nowrap">
                {{ $label }}
            </span>
        </div>

        @if (!$loop->last)
            <div class="w-16 h-0.5 border-white mx-1 border-2 mb-[22px]"></div>
        @endif
    @endforeach
</div>
