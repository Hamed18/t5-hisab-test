<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm Password – {{ config('app.name', 't5_hisab') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f9f9f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%; max-width: 24rem; }
        .btn { background: #4f46e5; color: white; padding: 0.5rem 1.25rem; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 600; }
        input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 0.375rem; }
        .form-group { margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Confirm Password</h2>
        <p style="margin-bottom: 1rem;">This is a secure area. Please confirm your password before continuing.</p>

        @if ($errors->any())
            <div style="color: #dc2626; margin-bottom: 1rem;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autofocus>
            </div>
            <button type="submit" class="btn">Confirm</button>
        </form>
    </div>
</body>
</html>
