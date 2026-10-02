<x-guest-layout>
    <head><link rel="stylesheet" href="{{ asset('css/tailwind.min.css') }}"><script src="{{ asset('css/all.min.js') }}"></script>
</head>
    <!-- تمرير العنوان للـ Layout -->
    <x-slot name="title">تسجيل الدخول</x-slot>

    <!-- حالة الجلسة (لرسائل النجاح أو الأخطاء) -->
    <x-auth-session-status class="mb-4 text-center font-medium" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- البريد الإلكتروني (ستايل محفور) -->
        <div class="relative group">
            <x-input-label for="email" :value="'البريد الإلكتروني'" class="text-[#5A511F] font-bold mb-1 mr-1" />
            <div class="relative">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400 group-focus-within:text-[#A18C2C] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="block w-full pr-10 py-3.5 bg-gray-50 border-none shadow-[inset_2px_2px_5px_rgba(0,0,0,0.05)] focus:ring-2 focus:ring-[#A18C2C]/20 rounded-2xl outline-none text-right transition-all"
                    placeholder="example@univ.edu">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
        </div>

        <!-- كلمة المرور -->
        <div class="relative group">
            <div class="flex justify-between items-center mb-1 mr-1">
                <x-input-label for="password" :value="'كلمة المرور'" class="text-[#5A511F] font-bold" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-[#C96734] hover:text-[#1E293B] transition-colors" href="{{ route('password.request') }}">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400 group-focus-within:text-[#A18C2C] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm6-10a4 4 0 00-4 4v2h8v-2a4 4 0 00-4-4z" />
                    </svg>
                </span>
                <input id="password" type="password" name="password" required
                    class="block w-full pr-10 py-3.5 bg-gray-50 border-none shadow-[inset_2px_2px_5px_rgba(0,0,0,0.05)] focus:ring-2 focus:ring-[#A18C2C]/20 rounded-2xl outline-none text-right transition-all"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
        </div>

        <!-- نوع الحساب -->
        <div>
            <x-input-label for="role" :value="'نوع الحساب (الدور)'" class="text-[#5A511F] font-bold mb-1 mr-1" />
            <select id="role" name="role" required
                class="block w-full py-3.5 bg-gray-50 border-none shadow-[inset_2px_2px_5px_rgba(0,0,0,0.05)] focus:ring-2 focus:ring-[#A18C2C]/20 rounded-2xl text-gray-600 outline-none text-right pr-10 transition-all cursor-pointer">
                <option value="" disabled selected>-- اختر دورك في النظام --</option>
                <option value="student">طالب</option>
                <option value="supervisor">مشرف</option>
                <option value="admin">إدارة</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-1 text-xs" />
        </div>

        <!-- تذكرني -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-[#1E293B] shadow-sm focus:ring-[#A18C2C]">
            <span class="ms-2 text-sm text-gray-500 font-medium">تذكرني على هذا الجهاز</span>
        </div>

        <!-- زر الدخول -->
        <div class="pt-2">
            <button type="submit" class="w-full py-4 rounded-2xl text-[#F6DCBD] font-black text-lg bg-[#1E293B] hover:bg-[#5A511F] active:scale-[0.98] transition-all duration-300 shadow-xl shadow-blue-900/10">
                تسجيل الدخول
            </button>
        </div>

        <!-- رابط لإنشاء حساب جديد -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500 font-medium">
                ليس لديك حساب؟
                <a href="{{ route('register') }}" class="text-[#A18C2C] font-bold hover:underline">أنشئ حساباً الآن</a>
            </p>
        </div>
    </form>
</x-guest-layout>
