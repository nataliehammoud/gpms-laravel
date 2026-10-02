<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPMS - نظام إدارة مشاريع التخرج</title>

    <!-- Font Awesome محلي -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <!-- Bootstrap محلي -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <style>
        @font-face {
            font-family: 'Cairo';
            src: url("{{ asset('fonts/Cairo-Regular.ttf') }}") format('truetype');
            font-weight: 400;
        }
        @font-face {
            font-family: 'Cairo';
            src: url("{{ asset('fonts/Cairo-Bold.ttf') }}") format('truetype');
            font-weight: 700;
        }

        * {
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: #f5f7fa;
        }

        /* Navbar */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 0;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: #1a2a4f !important;
            text-decoration: none;
        }

        .navbar-brand span {
            color: #c5a059;
        }

        .nav-link {
            color: #4a5568 !important;
            font-weight: 500;
            margin: 0 5px;
            text-decoration: none;
        }

        .nav-link:hover {
            color: #c5a059 !important;
        }

        .btn-login {
            background: #1a2a4f;
            color: white !important;
            padding: 8px 25px;
            border-radius: 30px;
            text-decoration: none;
        }

        .btn-login:hover {
            background: #c5a059;
            color: #1a2a4f !important;
        }

        /* Hero Section */
        .hero {
            padding: 80px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 42px;
            font-weight: 700;
            color: #1a2a4f;
            margin-bottom: 20px;
        }

        .hero h1 span {
            color: #c5a059;
        }

        .hero p {
            font-size: 18px;
            color: #6c757d;
            max-width: 600px;
            margin: 0 auto 30px;
        }

        /* About */
        .about-section {
            padding: 50px 0;
        }

        .about-title {
            color: #1a2a4f;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
            font-size: 28px;
        }

        .about-text {
            color: #6c757d;
            line-height: 1.8;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Footer */
        .footer {
            background: #1a2a4f;
            color: white;
            padding: 30px 0;
            text-align: center;
            margin-top: 60px;
        }

        .footer p {
            margin: 0;
            font-size: 13px;
            opacity: 0.8;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -15px;
        }

        .col-md-4 {
            width: 33.333%;
            padding: 15px;
            box-sizing: border-box;
        }

        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }



        @media (max-width: 768px) {
            .hero h1 {
                font-size: 28px;
            }
            .hero p {
                font-size: 16px;
            }
            .col-md-4 {
                width: 100%;
            }
            .navbar-container {
                flex-direction: column;
                gap: 15px;
            }
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container navbar-container">
            <a class="navbar-brand" href="/">GP<span>MS</span></a>

                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-login">لوحة التحكم</a>
                @else
                    <a href="{{ route('login') }}" class="btn-login">تسجيل الدخول</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <h1>نظام إدارة <span>مشاريع التخرج</span></h1>
            <p>منصة متكاملة لإدارة وتقييم مشاريع التخرج باستخدام تقنيات الذكاء الاصطناعي</p>
               <p class="about-text">
                GPMS هو نظام متخصص في إدارة مشاريع التخرج، يهدف إلى تسهيل عملية التقديم والتقييم والمتابعة
                بين الطلاب والمشرفين، مع الاستفادة من تقنيات الذكاء الاصطناعي لتقديم تحليلات دقيقة وتوصيات ذكية.
            </p>
            @guest
                <a href="{{ route('register') }}" class="btn-login" style="background: #c5a059; color: #1a2a4f; display: inline-block;">
                    <i class="fas fa-user-graduate"></i> حساب جديد
                </a>
            @endguest
        </div>
    </section>



    <!-- About -->
    <section id="about" class="about-section">

    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>© 2025 GPMS - نظام إدارة مشاريع التخرج</p>
        </div>
    </footer>

    <!-- Bootstrap JS محلي -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
