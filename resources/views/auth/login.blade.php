<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <!-- Bootstrap for quick layout -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            /* Dark blue background matching Image 3 */
            background-color: #001f4d; 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .login-card {
            /* Lighter blue/gray card background */
            background-color: #213555; 
            padding: 40px 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        .login-card h3 {
            color: #ffffff;
            font-weight: bold;
            margin-bottom: 25px;
            font-size: 24px;
        }
        .form-label {
            color: #ffffff;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .form-control {
            background-color: #f0f4f8;
            border: none;
            padding: 10px 15px;
        }
        .form-control:focus {
            box-shadow: none;
            border: 2px solid #00e5ff;
        }
        .btn-login {
            /* Cyan login button */
            background-color: #00e5ff;
            color: #000000;
            font-weight: bold;
            border: none;
            padding: 10px;
            border-radius: 6px;
            margin-top: 15px;
        }
        .btn-login:hover {
            background-color: #00b8cc;
        }
        .forgot-link {
            color: #00e5ff;
            text-decoration: none;
            font-size: 13px;
        }
        .forgot-link:hover {
            text-decoration: underline;
            color: #00b8cc;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 38px;
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h3 class="text-center">Employee Login</h3>

        <!-- Error Message Display -->
        @if(session('error'))
            <div class="alert alert-danger p-2 text-center" style="font-size: 14px;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Username or Email</label>
                <input type="text" name="username" class="form-control" placeholder="santhiya20122002" required>
            </div>

            <div class="mb-3 password-container">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="......" required>
                <i class="fas fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <button type="submit" class="btn btn-login w-100">Login</button>

            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
            </div>
        </form>
    </div>

    <!-- Small script to make the Eye icon reveal the password -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>