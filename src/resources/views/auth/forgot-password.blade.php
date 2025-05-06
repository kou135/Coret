@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#7fcbdc]">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/coret-logo.png') }}" alt="Coret" class="h-10">
        </div>
        
        <h2 class="text-xl font-semibold text-center mb-6">パスワード再発行</h2>
        
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="mb-2">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
                <input id="email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="e-mail">
                
                @error('email')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="text-xs text-gray-600 mb-6 mt-2">
                ご登録されているメールアドレス認証キーを送信します。
            </div>
            
            <div class="mb-4">
                <button type="submit" class="w-full bg-[#4a6da7] hover:bg-[#3a5d97] text-white font-medium py-2 px-4 rounded-md">
                    次へ
                </button>
            </div>
        </form>
        
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                ログイン画面へ戻る
            </a>
        </div>
    </div>
</div>
@endsection

