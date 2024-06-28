<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair log book</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Навигационное меню -->
    <header>
        <nav>
            <ul class="nav-links">
                <li><a href="{{ url('/posts') }}">Home</a></li>
            </ul>
            @if (Route::has('login'))
            @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
            @else
            <ul class="auth-links">
                <li><a href="{{ route('login') }}">LogIn</a></li>
                @if (Route::has('register'))
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endif
            </ul>
            @endauth
            @endif
        </nav>
    </header>

    <!-- Основной контент -->
    <main>
        <div class="content">
            <div class="centered-content">
                <h1>The machine repair log book</h1>
            </div>
            <div class="image-container">
                <img src="{{ asset('images/hammer.jpg') }}" alt="Image">
            </div>
        </div>
    </main>

    <!-- Футер -->
    <footer>
        <p>&copy; 2024 Your Company. All rights reserved.</p>
    </footer>
</body>
</html>