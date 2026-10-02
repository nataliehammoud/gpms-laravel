<x-guest-layout>
    <head></head>
    <x-slot name="title">انضم إلينا</x-slot>

    <!-- زر العودة -->
    <div class="relative mb-12">
        <a href="{{ url('/') }}" class="absolute -top-12 left-0 group flex flex-row-reverse items-center gap-2 bg-gray-50 border border-gray-100 px-4 py-2 rounded-full text-[#334155] transition-all duration-300 hover:bg-[#475569] hover:text-white hover:shadow-lg z-10">
            <svg class="w-4 h-4 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="text-xs font-black italic">العودة للرئيسية</span>
        </a>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 pt-4">
        @csrf

        <!-- الاسم الكامل (درجة كحلي هادي بدل الأصفر) -->
        <div class="relative group">
            <x-input-label for="name" :value="'الاسم الكامل'" class="text-[#475569] font-bold mb-1 mr-1" />
            <div class="relative">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400 group-focus-within:text-[#475569] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="block w-full pr-10 py-3.5 bg-gray-50/30 border-none shadow-[inset_2px_2px_5px_rgba(0,0,0,0.03)] focus:ring-2 focus:ring-[#475569]/20 rounded-2xl outline-none text-right transition-all text-[#334155]" placeholder="أدخل اسمك الثلاثي">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- البريد الإلكتروني -->
        <div class="relative group">
            <x-input-label for="email" :value="'البريد الإلكتروني'" class="text-[#475569] font-bold mb-1 mr-1" />
            <div class="relative">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400 group-focus-within:text-[#475569] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="block w-full pr-10 py-3.5 bg-gray-50/30 border-none shadow-[inset_2px_2px_5px_rgba(0,0,0,0.03)] focus:ring-2 focus:ring-[#475569]/20 rounded-2xl outline-none text-right transition-all text-[#334155]" placeholder="example@univ.edu">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- كلمة المرور -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="relative group">
                <x-input-label for="password" :value="'كلمة المرور'" class="text-[#475569] font-bold mb-1 mr-1" />
                <input id="password" type="password" name="password" required
                    class="block w-full px-4 py-3.5 bg-gray-50/30 border-none shadow-[inset_2px_2px_5px_rgba(0,0,0,0.03)] focus:ring-2 focus:ring-[#475569]/20 rounded-2xl outline-none text-right transition-all text-[#334155]" placeholder="••••••••">
            </div>
            <div class="relative group">
                <x-input-label for="password_confirmation" :value="'تأكيد الكلمة'" class="text-[#475569] font-bold mb-1 mr-1" />
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="block w-full px-4 py-3.5 bg-gray-50/30 border-none shadow-[inset_2px_2px_5px_rgba(0,0,0,0.03)] focus:ring-2 focus:ring-[#475569]/20 rounded-2xl outline-none text-right transition-all text-[#334155]" placeholder="••••••••">
            </div>
        </div>

        <!-- زر التسجيل (نفس الدرجة الزرقاء اللي بتحبها) -->
        <div class="pt-4">
            <button type="submit" class="w-full py-4 bg-[#475569] hover:bg-[#334155] text-[#F6DCBD] font-black rounded-2xl shadow-xl shadow-slate-200 transform transition-all duration-300 active:scale-95">
                إنشاء حساب جديد
            </button>
        </div>

        <!-- رابط تسجيل الدخول -->
        <div class="mt-6 pt-4 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-400">
                لديك حساب بالفعل؟
                <a href="{{ route('login') }}" class="text-[#475569] font-bold hover:text-[#334155] transition-colors ml-1">
                    تسجيل الدخول
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
