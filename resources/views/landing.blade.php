<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="theme-green">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Gombe SS Hub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('css-manifest.php') }}"></script>
    <script src="{{ asset('css-check.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-green: #10b981;
            --light-green: #d1fae5;
        }
        
        body {
            font-family: 'Ubuntu', sans-serif;
        }
        
        .hero-section {
            background-image: url('{{ asset('assets/archive-bg.jpg') }}');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 40px 20px;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            z-index: 1;
        }
        
        .hero-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 'linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0.05) 100%)';
            z-index: 2;
        }
        
        .hero-content {
            position: relative;
            z-index: 3;
            max-width: 900px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            padding: 60px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .logo-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-direction: column;
        }
        
        .logo-header i {
            color: var(--primary-green);
            font-size: 32px;
            margin-bottom: 8px;
        }
        
        .logo-header > div {
            text-align: center;
        }
        
        .site-title {
            color: #1f2937;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            text-align: center;
        }
        
        .site-subtitle {
            color: var(--primary-green);
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .tagline {
            font-size: 42px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 40px;
            letter-spacing: -1px;
            text-align: center;
        }
        
        .tagline-highlight {
            color: var(--primary-green);
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .feature-card {
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            background: white;
            transition: all 0.3s ease;
            text-align: center;
            min-height: 140px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .feature-card:nth-child(2) {
            border-color: var(--primary-green);
            background: rgba(16, 185, 129, 0.02);
        }
        
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.15);
        }
        
        .feature-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            border-radius: 12px;
            margin-bottom: 12px;
            color: var(--primary-green);
            font-size: 24px;
        }
        
        .feature-title {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            letter-spacing: 0.5px;
            line-height: 1.4;
        }
        
        .button-group {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        .btn-primary {
            background-color: var(--primary-green);
            color: white;
            transition: all 0.3s ease;
            border-radius: 50px;
            padding: 14px 40px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 15px;
        }
        
        .btn-primary:hover {
            background-color: #059669;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
            transition: all 0.3s ease;
            border-radius: 50px;
            padding: 12px 40px;
            font-weight: 600;
            background: transparent;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 15px;
        }
        
        .btn-secondary:hover {
            background-color: rgba(16, 185, 129, 0.1);
            color: #059669;
        }
        
        .footer-text {
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            margin-top: 30px;
        }
        
        .footer-text a {
            color: #9ca3af;
            text-decoration: none;
        }
        
        .footer-text a:hover {
            color: var(--primary-green);
        }
        
        .nav-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-in-out;
            opacity: 1;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Bar -->
        <nav class="nav-bar sticky top-0 z-50 py-4" style="display: none;">
            <div class="container mx-auto px-6 flex justify-between items-center">
                <div class="flex items-center">
                    <img class="h-12 w-auto mr-3" src="{{ asset('assets/gombe_logo.png') }}" alt="Gombe SS Hub">
                    <span class="text-xl font-bold" style="color: var(--primary-green)">Gombe SS Hub</span>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium">Home</a>
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium">About</a>
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium">Contact</a>
                    <a href="{{ url('/admin/login') }}" class="btn-primary">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="hero-section">
            <div class="hero-content animate-fade-in">
                <div class="logo-header">
                    <i class="fas fa-circle-notch"></i>
                    <div>
                        <div class="site-title">GOMBE SECONDARY SCHOOL HUB</div>
                        <div class="site-subtitle">MANAGEMENT SYSTEM</div>
                    </div>
                </div>
                
                <h2 class="tagline">
                    Simplify. Connect. <span class="tagline-highlight">Excel</span>
                </h2>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="feature-title">ACADEMICS<br>& GRADES</div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="feature-title">COMMUNITY &<br>COMMUNICATION</div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="feature-title">ADMINISTRATION<br>& OPERATIONS</div>
                    </div>
                </div>
                
                <div class="button-group">
                    <a href="{{ url('/admin/login') }}" class="btn-primary">
                        GET STARTED
                    </a>
                    <a href="#" class="btn-secondary">
                        <i class="fas fa-question-circle"></i>
                        LEARN MORE
                    </a>
                </div>
                
                <div class="footer-text">
                    © 2024 Gombe Secondary School Hub. All rights reserved. 
                    <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
                </div>
            </div>
        </header>


        
        <script>
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        </script>
    </div>
</body>
</html>