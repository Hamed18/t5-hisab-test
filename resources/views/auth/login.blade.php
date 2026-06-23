<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in – {{ config('app.name', 't5_hisab') }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, sans-serif;
            background: #f9f9f9;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }
        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 2rem;
            width: 100%;
            max-width: 24rem;
        }
        h2 { font-size: 1.5rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; }
        input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.375rem;
            font-size: 1rem;
        }
        .btn {
            display: inline-block;
            width: 100%;
            padding: 0.75rem;
            background: #4f46e5;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            margin-top: 0.5rem;
        }
        .btn:hover { background: #4338ca; }
        .error { color: #dc2626; font-size: 0.875rem; margin-bottom: 1rem; }
        .link { text-align: center; margin-top: 1rem; font-size: 0.875rem; }
        .link a { color: #4f46e5; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Log in</h2>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" id="remember" name="remember" style="width: auto;">
                <label for="remember" style="margin-bottom: 0;">Remember me</label>
            </div>

            <button type="submit" class="btn">Log in</button>
        </form>

        <div class="link">
            @if (Route::has('register'))
                Don't have an account? <a href="{{ route('register') }}">Register</a>
            @endif
            <br>
            <a href="{{ route('password.request') }}">Forgot your password?</a>
        </div>
    </div>
</body>
</html>
