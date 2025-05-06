<x-loginlayout>
    
<style>
    
    body {
        background-image: url('{{ asset('images/logo2.png') }}');
        background-size: 100%; /* Only cover 85% */
        background-blend-mode: overlay;
    }

    .login-card {
        background: rgb(175, 236, 227);
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .login-card h2 {
        margin-bottom: 1rem;
    }

    .login-card .avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #e0f2f1;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        margin: 0 auto 1rem;
    }

    .login-card input[type="email"],
    .login-card input[type="password"] {
        width: 100%;
        padding: 0.75rem;
        margin-bottom: 1rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .login-card button,
    .login-card .register-link {
        width: 100%;
        padding: 0.75rem;
        margin-top: 0.5rem;
        background-color: #009688;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: block;
        text-decoration: none;
        text-align: center;
        box-sizing: border-box;
        font-size: 1rem;
    }

    .login-card .register-link {
        background-color: #00796b;
    }

    .login-card label {
        float: left;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .remember {
        text-align: left;
        margin-bottom: 1rem;
    }

    .alert {
        margin-top: 1rem;
        color: red;
    }
</style>


    <div class="login-card">
        <h2></h2>
        <div class="avatar">EAS</div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div>
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="remember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>

            <button type="submit">Log In</button>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </form>

        <!-- Register Link -->
        <a href="{{ route('register') }}" class="register-link">Create an Account</a>
    </div>
</x-loginlayout>
