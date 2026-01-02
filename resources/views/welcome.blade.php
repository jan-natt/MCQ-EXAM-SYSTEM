<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel - Welcome</title>

    <!-- FORCE WEB FONT (SYSTEM-INDEPENDENT) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', Arial, sans-serif !important;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            background-image: url('/assets/pngtree-creative-synthesis-education-background-picture-image_1617742.jpg');
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            background-size: cover;
            background-position: center;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 40px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6542c6ff, #454bb8ff);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #342db4ff;
            font-size: 20px;
            font-weight: 800;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #2f65b0ff;
        }

        .nav-links {
            display: flex;
            gap: 16px;
        }

        .nav-links a {
            padding: 10px 24px;
            text-decoration: none;
            color: #4b5563;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background: #f3f4f6;
            color: #1f2937;
        }

        .nav-links a.btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #ffffff;
        }

        .hero {
            text-align: center;
            padding: 60px 40px;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 16px;
        }

        .hero p {
            font-size: 18px;
            color: #6b7280;
            max-width: 600px;
            margin: auto;
        }

        .content {
            padding: 60px 40px;
        }

        .content h2 {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 48px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 32px;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
        }

        .card-icon {
            font-size: 28px;
            margin-bottom: 16px;
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #1f2937;
        }

        .card p {
            color: #6b7280;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            padding: 28px;
            background: #f9fafb;
            font-size: 14px;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 36px;
            }

            .content {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="logo">
            <div class="logo-icon">M</div>
            <span class="logo-text">Live-Mcq</span>
        </div>

        @if (Route::has('login'))
            <div class="nav-links">
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </div>

    <div class="hero">
        <h1>Smart Online Exam System</h1>
        <p>Secure, fast, and reliable digital examinations.</p>
    </div>

    <div class="content">
        <h2>Start Your Journey</h2>
        <div class="cards">
            <a href="#" class="card" target="_blank">
                <div class="card-icon">📚</div>
                <h3>Your Exam. Your Time. Your Place.</h3>
                <p>Attend exams anytime, anywhere..</p>
            </a>

            <a href="#" class="card" target="_blank">
                <div class="card-icon">🎥</div>
                <h3>Exam Smarter, Not Harder</h3>
                <p>Conduct exams online with confidence.</p>
            </a>

            <a href="#" class="card" target="_blank">
                <div class="card-icon">🚀</div>
                <h3>Trust & Security</h3>
                <p>Secure Online Examination Platform</p>
            </a>
        </div>
    </div>

    <div class="footer">
     © 2026 Online Exam System.
Secure • Reliable • Smart Examination Platform
    </div>
</div>

</body>
</html>
