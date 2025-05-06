@props([
    'hover_text' => 'ホバー説明',
    'align' => 'center', // left / center / right
    'tooltipClass' => '',
])

@php
    $tooltipAlignClass = match ($align) {
        'left' => 'left-0 translate-x-0',
        'right' => 'right-0 translate-x-0',
        default => 'left-1/2 -translate-x-1/2',
    };
@endphp

<div class="relative inline-block">
    <!-- 「？」マーク -->
    <div class="group w-6 h-6 bg-[#2BC7BC] text-white font-bold text-[14px] rounded-full flex items-center justify-center cursor-pointer relative z-50">
        ？
        <!-- 吹き出し -->
        <div class="absolute mt-7 {{ $tooltipAlignClass }} {{ $tooltipClass }}
            opacity-0 transform translate-y-[10px] 
            group-hover:opacity-100 group-hover:translate-y-0
            bg-[#FAFAFA]/90 text-gray text-[16px]
            rounded-md px-10 py-8 z-[9999] border border-[#2BC7BC]
            shadow-md w-[max-content] max-w-[800px]
            whitespace-normal leading-relaxed text-left 
            transition-all duration-300 ease-in-out pointer-events-none">
            {!! $slot !!}
        </div>
    </div>
</div>