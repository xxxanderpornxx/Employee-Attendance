<x-loginlayout>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">

    
    <style>
        body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: rgb(200, 237, 232) url('/images/logo2.png') no-repeat center center;
    background-size: 100%; /* Only cover 85% */
    background-blend-mode: overlay; /* Optional: creates faded effect */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
        button[type="submit"] {
            background-color: #009688; /* Green */
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            margin-top: 1rem;
        }

        button[type="submit"]:hover {
            background-color:  #00796b;
        }

        .alert {
            margin-top: 1rem;
            color: red;
        }

         .container {
            background: rgb(175, 236, 227);
            padding: 2rem;
            max-width: 600px; /* Increase this value to make it wider */
            width: 100%;       /* Ensures it fills up to max-width */
            margin: 2rem auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }


        label {
            display: block;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>

    <div class="container">
        <form method="POST" action="{{ route('register.create') }}">
            @csrf

            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="hr">HR</option>
                    <option value="manager">Manager</option>
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                </select>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit">Register</button>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
</x-loginlayout>