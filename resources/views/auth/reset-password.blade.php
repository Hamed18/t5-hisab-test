<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password – {{ config('app.name', 't5_hisab') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f9f9f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%; max-width: 24rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-size: 0.875rem; margin-bottom: 0.25rem; }
        input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 0.375rem; }
        .btn { background: #4f46e5; color: white; padding: 0.5rem 1.25rem; border: none; border-radius: 0.375rem; cursor: pointer; }
        .btn:hover { background: #4338ca; }
        .error { color: #dc2626; font-size: 0.875rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Reset Password</h2>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label for="password">New Password</label>
                <input id="password" type="password" name="password" required autofocus>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</body>
</html>
