<div class="bg-gray-100 min-h-screen">
    {{-- Main content area --}}
    <div class="bg-white mx-4 rounded-t-md shadow-sm">
        <div class="flex items-center px-6 py-4 border-b border-gray-200">
            {{-- Logo/placeholder --}}
            <div class="w-24 h-12 bg-gray-300 mr-8"></div>
            
            {{-- Department name --}}
            <h1 class="text-2xl text-gray-700 font-medium mr-auto">{{ $name }}</h1>
            
            {{-- Staff list link --}}
            <a href="#" class="flex items-center text-gray-500 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span>役職員一覧</span>
            </a>
        </div>
    </div>
</div>