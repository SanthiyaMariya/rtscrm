<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - RTS CRM</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Custom Colors matching your image */
        body {
            background-color: #022046; /* Dark Blue Background */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .forgot-card {
            background-color: #243b55; /* Slightly lighter blue-gray for the card */
            border-radius: 12px;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-cyan {
            background-color: #00e5ff; /* Bright Cyan Button */
            color: #000;
            font-weight: 600;
            border: none;
            border-radius: 6px;
        }

        .btn-cyan:hover {
            background-color: #00cce6;
            color: #000;
        }

        .link-cyan {
            color: #00e5ff;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .link-cyan:hover {
            color: #00cce6;
            text-decoration: underline;
        }

        .form-control {
            border-radius: 6px;
            padding: 0.75rem 1rem;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center">
        <div class="forgot-card">
            <h3 class="text-white text-center mb-4 fw-bold">Forgot Password</h3>

              <!-- ADD THE MESSAGES HERE -->
    @if(session('status'))
        <div class="alert alert-success p-2 text-center" style="font-size: 14px;">
            {{ session('status') }}
        </div>
    @endif

    @error('email')
        <div class="alert alert-danger p-2 text-center" style="font-size: 14px;">
            {{ $message }}
        </div>
    @enderror

            
            {{-- Update the action route to match your actual route for sending OTP --}}
            <form action="{{ route('password.otp') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="text-white mb-2" style="font-size: 0.9rem;">Enter Registered Email</label>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                </div>
                
                <button type="submit" class="btn btn-cyan btn-lg w-100 mb-3">Send OTP</button>
                
                <div class="text-center">
                    {{-- Update the href to your login route, e.g., route('login') --}}
                    <a href="{{ route('login') }}" class="link-cyan">Back to Login</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>