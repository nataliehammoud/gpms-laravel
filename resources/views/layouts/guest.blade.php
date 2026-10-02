<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'نظام GPMS' }}</title>
    <link rel="stylesheet" href="{{ asset('css/tailwind.min.css') }}"><script src="{{ asset('css/all.min.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* حركة الطبقة البيضاء الأساسية */
        @keyframes liquid-flow-main {
            0%, 100% { d: path('M80 0 C 50 30 60 70 0 100 L 0 100 L 0 0 Z'); }
            50% { d: path('M85 0 C 55 40 45 60 0 100 L 0 100 L 0 0 Z'); }
        }
        /* حركة الطبقة الملونة الخلفية */
        @keyframes liquid-flow-back {
            0%, 100% { d: path('M95 0 C 65 30 75 70 10 100 L 0 100 L 0 0 Z'); }
            50% { d: path('M100 0 C 70 40 60 60 15 100 L 0 100 L 0 0 Z'); }
        }
        .animate-liquid-main { animation: liquid-flow-main 8s ease-in-out infinite; }
        .animate-liquid-back { animation: liquid-flow-back 12s ease-in-out infinite; }
    </style>
</head>
<body class="antialiased font-sans bg-white overflow-hidden">
    @php
        $isRegister = request()->routeIs('register');
        /* تعديل اللون: الكحلي الداكن للوج إن، والكحلي الفاتح (نفس لون زرك) للريجيستر */
        $mainColor = $isRegister ? '#475569' : '#1E293B';
    @endphp

    <div class="min-h-screen flex {{ $isRegister ? 'flex-row-reverse' : '' }}">

        <!-- القسم الأول: المحتوى البصري -->
        <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center overflow-hidden transition-colors duration-1000"
             style="background-color: {{ $mainColor }};">

            <!-- الزحلوطة المتحركة -->
            <div class="absolute inset-y-0 {{ $isRegister ? '-right-1 rotate-180' : '-left-1' }} w-80 z-20 pointer-events-none">
                <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <!-- تدرج لوني يناسب اللون الكحلي الجديد وبدون أصفر -->
                        <linearGradient id="wave-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#94a3b8;stop-opacity:0.4" />
                            <stop offset="100%" style="stop-color:#ffffff;stop-opacity:0.1" />
                        </linearGradient>
                    </defs>
                    <!-- الطبقة الملونة الخلفية (أخذت التدرج الكحلي/الرمادي الجديد) -->
                    <path class="animate-liquid-back" d="M95 0 C 65 30 75 70 10 100 L 0 100 L 0 0 Z" fill="url(#wave-grad)" />
                    <!-- الطبقة البيضاء الأساسية -->
                    <path class="animate-liquid-main" d="M80 0 C 50 30 60 70 0 100 L 0 100 L 0 0 Z" fill="white" />
                    <!-- الخط الفاصل (حولناه لرمادي ليتماشى مع الكحلي بدل الذهبي) -->
                    <path class="animate-liquid-main" d="M80 0 C 50 30 60 70 0 100" fill="none" stroke="#94a3b8" stroke-width="0.15" opacity="0.3" />
                </svg>
            </div>

            <div class="relative z-10 text-center p-12">
                <h2 class="text-7xl font-black text-[#F6DCBD] mb-4 tracking-tighter drop-shadow-xl animate-pulse">GPMS</h2>
                <!-- الفاصل الصغير صار بلون كحلي فاتح متناسق -->
                <div class="h-1.5 w-24 bg-white/20 mx-auto mb-8 rounded-full shadow-lg"></div>
                <p class="text-[#D6DFE1] text-xl max-w-md mx-auto leading-relaxed font-light bold">
{{ $isRegister ? 'تسجيل حساب جديد' : 'تسجيل الدخول' }}                </p>
            </div>

            <!-- تأثير الضوء الخلفي (أبيض بدل أصفر) -->
            <div class="absolute top-1/3 left-1/4 w-96 h-96 bg-white/10 rounded-full blur-[120px]"></div>
        </div>

        <!-- القسم الثاني: الفورم -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white overflow-y-auto">
            <div class="w-full max-w-md">
                <div class="text-center mb-10">
                    <h1 class="text-4xl font-black mb-3 transition-colors duration-1000" style="color: {{ $mainColor }};">
                        {{ $title }}
                    </h1>
                    <!-- الفاصل تحت العنوان صار بنفس اللون الكحلي -->
                    <div class="h-1 w-12 mx-auto rounded-full" style="background-color: {{ $mainColor }}; opacity: 0.2;"></div>
                </div>

                <div class="relative">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
