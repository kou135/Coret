<div class="w-[190px] bg-gray h-screen fixed text-white flex flex-col">

    <div class="p-4 border-b border-gray-500">
        <div><img src="{{ asset('img/logo.svg') }}" alt="" class="w-32 h-auto"></div>
    </div>

    <nav class="mt-6 flex flex-col h-[calc(100%-64px)]">
        <a href="{{ route('home') }}"
            class="flex items-center pl-6 pr-5 py-3 hover:bg-gray-500 {{ request()->routeIs('home') ? 'bg-gray-500 text-bar' : '' }} hover:underline cursor-pointer">
            <span class="mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </span>
            <span>ホーム</span>
        </a>

        <a href="{{ route('measures.index') }}"
            class="flex items-center pl-6 pr-5 py-3 mt-2 hover:bg-gray-500 {{ request()->routeIs('measures.*') ? 'bg-gray-500 text-bar' : '' }} hover:underline cursor-pointer">
            <span class="mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
            </span>
            <span>施策一覧</span>
        </a>

        <div class="flex-grow"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center pl-6 pr-5 py-3 mb-7 hover:bg-gray-500 text-left cursor-pointer">
                <span class="mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </span>
                <span>ログアウト</span>
            </button>
        </form>
    </nav>
</div>
