<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h1>Admin Login</h1>

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Email"
            required
        >

        @error('email')
            <p>{{ $message }}</p>
        @enderror

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <label>
            <input type="checkbox" name="remember" value="1">
            Remember me
        </label>

        <button type="submit" class="mg-2">Login</button>
    </form>
</body>
</html>