<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email – {{ config('app.name', 't5_hisab') }}</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            background: #f9f9f9; color: #1a1a1a;
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; padding: 1rem;
        }
        .card {
            background: white; border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 2rem; max-width: 24rem; width: 100%; text-align: center;
        }
        .btn {
            display: inline-block; margin-top: 1rem; padding: 0.5rem 1.25rem;
            background: #4f46e5; color: white; border: none;
            border-radius: 0.375rem; cursor: pointer; font-weight: 600;
        }
        .status { color: #065f46; background: #d1fae5; padding: 0.5rem; border-radius: 0.25rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Verify Your Email Address</h2>
        <p style="margin: 1rem 0;">Thanks for signing up! Before getting started, please verify your email by clicking the link we just sent to <strong>{{ Auth::user()->email }}</strong>.</p>

        @if (session('status') === 'verification-link-sent')
            <div class="status">A new verification link has been sent.</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #6b7280; cursor: pointer;">Log out</button>
        </form>
    </div>
</body>
</html>
