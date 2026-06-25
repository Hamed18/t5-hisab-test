<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 't5_hisab') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <style>
        /* Minimal inline CSS for a clean, centered layout */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; -webkit-text-size-adjust: 100%; }
        body {
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            line-height: 1.5;
            color: #1a1a1a;
            background: #f9f9f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .container {
            width: 100%;
            max-width: 32rem;
            margin: 0 auto;
            text-align: center;
        }

        /* Navigation */
        header { margin-bottom: 2rem; }
        nav {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        nav a {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: 0.375rem;
            border: 1px solid #ccc;
            color: #333;
            background: #fff;
            transition: background 0.2s, border-color 0.2s;
        }
        nav a:hover { background: #eee; border-color: #999; }
        nav a.register {
            background: #4f46e5;
            color: #fff;
            border-color: #4f46e5;
        }
        nav a.register:hover { background: #4338ca; }

        /* Main content */
        main { margin-top: 1rem; }
        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #111;
        }
        .subtitle {
            font-size: 1.125rem;
            color: #555;
            margin-bottom: 2rem;
        }
        .actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: background 0.2s;
        }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-primary:hover { background: #4338ca; }
        .btn-secondary { background: #fff; color: #333; border: 1px solid #ccc; }
        .btn-secondary:hover { background: #f0f0f0; }

        footer {
            margin-top: 3rem;
            font-size: 0.75rem;
            color: #888;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @if (session('status') === 'account-deleted')
        <div style="background: #d1fae5; color: #065f46; padding: 0.5rem 1rem; border-radius: 0.375rem; text-align: center; margin-bottom: 1rem;">
            Your account has been permanently deleted.
        </div>
    @endif

    <div class="container">
        <!-- Authentication links (top right) -->
        @if (Route::has('login'))
            {{-- <header class="w-full flex justify-center py-4">
    <nav class="flex items-center gap-6">
        @auth
            <a href="{{ url('/dashboard') }}" class="text-slate-300 hover:text-white transition">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="text-slate-300 hover:text-white transition">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-semibold transition">Register</a>
            @endif
        @endauth
    </nav>
</header> --}}
        @endif

        <!-- Main content -->
        <main>
            <h1>{{ config('app.name', 't5_hisab') }}</h1>
            <p class="subtitle text-black">An Internal Financial Management System of Top5Way</p>

            <div class="actions">
                @guest
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-secondary">Get Started</a>
                    @endif
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="btn btn-secondary">Already have an Account</a>
                    @endif
                @else
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                @endauth
            </div>
        </main>

        <footer>
            &copy; {{ date('Y') }} {{ config('app.name', 't5_hisab') }}. All rights reserved.
        </footer>
    </div>
</body>
</html>
