<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Your Journey</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth_page.css') }}">
</head>

<body>
    <div id="site-preloader">
        <div class="loader-content">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <p>Preparing your journey...</p>
        </div>
    </div>
    <div class="container">
        <div class="image-section">
            <div class="image-content">
                <h2>Explore<br>The World</h2>
                <p>Your next great adventure starts right here.</p>
            </div>
        </div>

        <div class="form-section">
            <div class="top-nav">
                <a href="{{ url('/index') }}">← Back</a>
            </div>

            @if($error)<div class="msg error">{{ $error }}</div>@endif
            @if($success)<div class="msg success">{{ $success }}</div>@endif

            @php
            $activeForm = old('form_type', $form_type ?? 'signup');
            @endphp
            <div class="form-wrapper {{ $activeForm === 'signup' ? 'active' : '' }}" id="signup-form">
                <h3>Create Account</h3>
                <p class="toggle-text">Already a member? <span onclick="toggleForm()">Log In</span></p>
                <form method="POST">
                    @csrf
                    <input type="hidden" name="form_type" value="signup">
                    <input type="text" name="username" placeholder="Full Name" value="{{ old('username') }}" required>
                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                    <input type="number" name="phone" placeholder="Phone Number" value="{{ old('phone') }}" required>
                    <input type="password" name="password" placeholder="Create Password" required>
                    <button type="submit">Sign Up</button>
                </form>
                <a href="{{ url('/auth/google') }}" class="google-btn">
                    <img src="{{ asset('images/auth-page/google-img.svg') }}" alt="Google logo">
                    <span>Sign Up with Google</span>
                </a>
            </div>

            <div class="form-wrapper {{ $activeForm === 'login' ? 'active' : '' }}" id="login-form">
                <h3>Welcome Back</h3>
                <p class="toggle-text">New here? <span onclick="toggleForm()">Sign Up</span></p>

                <form method="POST">
                    @csrf

                    <input type="hidden" name="form_type" value="login">

                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                    <input type="password" name="password" placeholder="Password" required>

                    <button type="submit">Log In</button>

                    <a href="{{ url('/auth/google') }}" class="google-btn">
                        <img src="{{ asset('images/auth-page/google-img.svg') }}" alt="Google logo">
                        <span>Login with Google</span>
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/auth_page.js') }}"></script>
    <script>
        function lazyLoad() {
            const preloader = document.getElementById('site-preloader')

            if (!preloader) return

            setTimeout(() => {
                preloader.style.opacity = '0'
                setTimeout(() => {
                    preloader.style.display = 'none'
                }, 500)
            }, 200)
        }

        window.addEventListener('DOMContentLoaded', () => {
            lazyLoad()
        })
    </script>
</body>

</html>