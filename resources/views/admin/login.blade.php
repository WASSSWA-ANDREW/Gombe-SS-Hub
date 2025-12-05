<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Gombe SS Hub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('css-manifest.php') }}"></script>
    <script src="{{ asset('css-check.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 1100px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        
        @media (max-width: 768px) {
            .login-card {
                grid-template-columns: 1fr;
                max-width: 400px;
            }
        }
        
        .login-image-section {
            background: linear-gradient(135deg, #6ee7b7 0%, #10b981 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .login-image-section {
                display: none;
            }
        }
        
        .login-image-section::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -50px;
            left: -50px;
        }
        
        .login-image-section::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -30px;
            right: -30px;
        }
        
        .image-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }
        
        .image-placeholder {
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .image-placeholder img {
            max-width: 180px;
            height: auto;
        }
        
        .login-form-section {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        @media (max-width: 768px) {
            .login-form-section {
                padding: 40px 30px;
            }
        }
        
        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .logo-wrapper img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
            padding: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            text-align: center;
            margin-bottom: 10px;
        }
        
        .login-subtitle {
            font-size: 14px;
            color: #718096;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #f7fafc;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #10b981;
            background: white;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .form-input::placeholder {
            color: #a0aec0;
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 13px;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
        }
        
        .checkbox-wrapper input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #5b7cfa;
        }
        
        .checkbox-wrapper label {
            color: #4a5568;
            cursor: pointer;
            font-size: 13px;
        }
        
        .forgot-password {
            color: #10b981;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #059669;
        }
        
        .submit-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        
        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        
        .submit-button:active {
            transform: translateY(0);
        }
        
        .footer-text {
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-image-section">
                <div class="image-content">
                    <div class="image-placeholder">
                        <img src="{{ asset('assets/gombe_logo.png') }}" alt="Admin Side Image">
                    </div>
                </div>
            </div>
            
            <div class="login-form-section">
                <div class="logo-wrapper">
                    <img src="{{ asset('assets/gombe_logo.png') }}" alt="Logo">
                </div>
                
                <h1 class="login-title">Admin Login</h1>
                <p class="login-subtitle">Welcome back! Please sign in to continue.</p>
                
                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="admin@example.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                    </div>
                    
                    <div class="form-footer">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="submit-button">Sign In</button>
                </form>
                
                <p class="footer-text">&copy; {{ date('Y') }} Gombe SS Hub. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>