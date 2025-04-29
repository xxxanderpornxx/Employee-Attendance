<x-loginlayout>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <div class="container">
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
        </form>

    </div>

</x-loginlayout>
