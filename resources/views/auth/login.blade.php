<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ambacinema</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-xl border border-slate-200">
        <h2 class="text-3xl font-extrabold text-blue-600 mb-6 text-center italic tracking-tighter">Amba<span class="text-slate-900">cinema</span></h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-slate-900">
                @error('email')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <input id="password" type="password" name="password" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-slate-900">
                @error('password')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-4">
                <div class="text-sm">
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">Don't have an account?</a>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-white transition-colors">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</body>
</html>
