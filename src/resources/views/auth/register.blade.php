<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-[#7dcbda]">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-8">
        <div class="flex justify-center mb-6">
            <img src="../../../img/Coret.svg" alt="coret" class="w-28 h-16">
        </div>

        <h1 class="font-poppins font-bold  text-center text-xl text-gray-600 mb-8">新規登録</h1>

        <div class="flex items-center mb-8">
            <div class="h-px bg-gray-600 flex-1"></div>
            <span class="px-4 text-gray-600 font-roboto">個人情報</span>
            <div class="h-px bg-gray-600 flex-1"></div>

            <div class="px-8"></div>

            <div class="h-px bg-gray-600 flex-1"></div>
            <span class="px-4 text-gray-600">職務情報</span>
            <div class="h-px bg-gray-600 flex-1"></div>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                <!-- Personal Information Section -->
                <div class="space-y-4">
                    <div>
                        <label for="firstName" class="block text-sm mb-1 text-gray-600">苗字</label>
                        <input
                            id="firstName"
                            name="first_name"
                            type="text"
                            placeholder="First name"
                            class="w-full border rounded-md px-3 py-6px placeholder-gray-400 bg-lightBlue border-blue"
                            value="{{ old('first_name') }}"
                        >
                        @error('first_name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="lastName" class="block text-sm mb-1 text-gray-600">名前</label>
                        <input 
                            id="lastName" 
                            name="last_name"
                            type="text"
                            placeholder="Last name" 
                            class="w-full border rounded-md px-3 py-6px placeholder-gray-400 bg-lightBlue border-blue"
                            value="{{ old('last_name') }}"
                        >
                        @error('last_name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm mb-1 text-gray-600">メールアドレス</label>
                        <input 
                            id="email" 
                            name="email"
                            type="email" 
                            placeholder="e-mail" 
                            class="w-full border rounded-md px-3 py-6px placeholder-gray-400 bg-lightBlue border-blue"
                            value="{{ old('email') }}"
                        >
                        @error('email')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm mb-1 text-gray-600">パスワード</label>
                        <div>
                            <div class="relative">
                                <input 
                                    id="password" 
                                    name="password"
                                    type="password" 
                                    placeholder="password" 
                                    class="w-full border rounded-md px-3 py-6px pr-10 placeholder-gray-400 bg-lightBlue border-blue"
                                >
                                <button 
                                    type="button"
                                    id="togglePassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-off-icon hidden">
                                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                        <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                        <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                        <line x1="2" x2="22" y1="2" y2="22"></line>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Job Information Section -->
                <div class="space-y-4">
                    <div>
                        <label for="position" class="block text-sm mb-1 text-gray-600">役職</label>
                        <input 
                            id="position" 
                            name="position"
                            type="text"
                            placeholder="post" 
                            class="w-full border rounded-md px-3 py-6px placeholder-gray-400 bg-lightBlue border-blue"
                            value="{{ old('position') }}"
                        >
                        @error('position')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="department" class="block text-sm mb-1 text-gray-600">部署名</label>
                        <div class="relative">
                            <select 
                                id="department" 
                                name="department"
                                class="w-full border rounded-md px-3 py-6px appearance-none pr-10 text-gray-400 bg-lightBlue border-blue"
                            >
                                <option value="" selected disabled>Department Name</option>
                                <option value="engineering" {{ old('department') == 'engineering' ? 'selected' : '' }}>A部署</option>
                                <option value="marketing" {{ old('department') == 'marketing' ? 'selected' : '' }}>B部署</option>
                                <option value="sales" {{ old('department') == 'sales' ? 'selected' : '' }}>C部署</option>
                                <option value="hr" {{ old('department') == 'hr' ? 'selected' : '' }}>D部署</option>
                                <option value="finance" {{ old('department') == 'finance' ? 'selected' : '' }}>E部署</option>
                            </select>
                            {{-- <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div> --}}
                            @error('department')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 flex flex-col items-center">
                <button
                    type="submit"
                    class="font-medium font-poppins bg-moreBlue hover:bg-hoverMoreBlue text-white w-44 py-3 rounded-md">
                    登録
                </button>
                
                <a href="{{ route('login') }}" class="mt-3 text-13 text-moreBlue hover:underline font-semibold">
                    すでに登録済みの方はこちら
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeIcon = document.querySelector('.eye-icon');
            const eyeOffIcon = document.querySelector('.eye-off-icon');
            
            togglePassword.addEventListener('click', function() {
                // Toggle the password visibility
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle the eye icons
                eyeIcon.classList.toggle('hidden');
                eyeOffIcon.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>