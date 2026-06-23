<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password – {{ config('app.name', 't5_hisab') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f9f9f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%; max-width: 24rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-size: 0.875rem; margin-bottom: 0.25rem; }
        input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 0.375rem; }
        .btn { background: #4f46e5; color: white; padding: 0.5rem 1.25rem; border: none; border-radius: 0.375rem; cursor: pointer; }
        .btn:hover { background: #4338ca; }
        .status { background: #d1fae5; color: #065f46; padding: 0.5rem; border-radius: 0.25rem; margin-bottom: 1rem; }
        .error { color: #dc2626; font-size: 0.875rem; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Forgot Password</h2>
        <p style="margin-bottom: 1rem;">Forgot your password? No problem. Just enter your email and we'll send you a reset link.</p>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="error" style="margin-bottom: 1rem;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <button type="submit" class="btn">Email Password Reset Link</button>
        </form>

        <p style="margin-top: 1rem;">
            <a href="{{ route('login') }}" style="color: #4f46e5;">Back to login</a>
        </p>
    </div>
</body>
</html>
