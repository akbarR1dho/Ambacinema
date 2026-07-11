@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-200">
        <div class="text-center mb-8">
            <div class="flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 sm:h-12 mr-4 sm:mr-5">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-blue-600 tracking-tighter uppercase italic">Amba<span class="text-slate-900">cinema</span></h2>
            </div>
            <p class="mt-2 text-sm text-slate-500">{{ __('Sign in to your account') }}</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">{{ __('Email Address') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full bg-slate-50 border border-slate-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 transition-colors">
                @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">{{ __('Password') }}</label>
                <div class="relative mt-1">
                    <input id="password" type="password" name="password" required class="block w-full bg-slate-50 border border-slate-300 rounded-xl shadow-sm py-3 pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 transition-colors">
                    <button type="button" onclick="const p = document.getElementById('password'); const open = document.getElementById('eye-open-login'); const closed = document.getElementById('eye-closed-login'); if(p.type==='password'){p.type='text';open.classList.add('hidden');closed.classList.remove('hidden');}else{p.type='password';open.classList.remove('hidden');closed.classList.add('hidden');}" class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-blue-600 focus:outline-none transition-colors">
                        <!-- Eye Open Icon (Default) -->
                        <svg id="eye-open-login" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye Closed Icon -->
                        <svg id="eye-closed-login" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row justify-between gap-4">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-5 w-5 border-slate-300 rounded transition-colors cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm font-medium text-slate-700 select-none cursor-pointer">
                        {{ __('Remember me') }}
                    </label>
                </div>
                <div class="text-sm">
                    <span class="text-slate-500">{{ __("Don't have an account?") }}</span>
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-bold ml-1 transition-colors">{{ __('Sign up') }}</a>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-white transition-all transform hover:-translate-y-0.5">
                    {{ __('Sign in') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
