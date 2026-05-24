<div class="fi-simple-layout flex min-h-screen items-center justify-center p-4 md:p-6">
    
    {{-- Animated Background (floating medical bubbles & heartbeat ECG line) --}}
    @include('filament.login-bg')

    <div class="fi-simple-main w-full max-w-[420px] z-10">
        
        {{-- Custom Branding Header --}}
        <div class="flex flex-col items-center gap-4 mb-8 text-center">
            <div class="w-[90px] h-[90px] bg-white/15 border-2 border-white/35 rounded-[18px] overflow-hidden flex items-center justify-center backdrop-blur-xl shadow-2xl p-2.5 transition-transform duration-500 hover:scale-105">
                <img
                    src="{{ asset('img/logo.png') }}"
                    alt="Logo {{ config('app.name') }}"
                    class="w-full h-full object-contain filter drop-shadow-md rounded-[12px]"
                >
            </div>
            <div>
                <h1 class="font-sans font-extrabold text-[1.65rem] tracking-tight text-white/95 drop-shadow-md leading-tight">
                    {{ config('app.name') }}
                </h1>
                <p class="font-sans font-semibold text-[0.78rem] tracking-[0.08em] text-white/60 uppercase mt-1">
                    Sistem Informasi Manajemen Puskesmas
                </p>
            </div>
        </div>

        {{-- Glassmorphic Form Card --}}
        <div class="fi-simple-main-card p-6 md:p-8 rounded-[1.75rem] border border-white/25 bg-white/10 backdrop-blur-3xl shadow-[0_12px_40px_rgba(0,0,0,0.25)] transition-all duration-300">
            <form wire:submit.prevent="authenticate" class="space-y-5">
                
                {{-- Session Status / General errors --}}
                @if (session()->has('status'))
                    <div class="p-3 text-sm text-green-300 bg-green-500/20 border border-green-500/35 rounded-xl">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Username Field --}}
                <div class="space-y-1.5">
                    <label for="username" class="block text-xs font-semibold text-white/80 tracking-wide">
                        Username
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="fa-solid fa-user text-white/40 text-sm"></i>
                        </span>
                        <input
                            wire:model.defer="username"
                            type="text"
                            id="username"
                            required
                            autofocus
                            placeholder="Masukkan username"
                            class="block w-full pl-11 pr-4 py-2.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/35 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/40 focus:border-cyan-300/60 focus:bg-white/15 transition-all duration-200"
                        >
                    </div>
                    @error('username')
                        <p class="text-xs font-medium text-rose-300 mt-1 pl-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label for="password" class="block text-xs font-semibold text-white/80 tracking-wide">
                            Password
                        </label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="fa-solid fa-lock text-white/40 text-sm"></i>
                        </span>
                        <input
                            wire:model.defer="password"
                            type="password"
                            id="password"
                            required
                            placeholder="Masukkan password"
                            class="block w-full pl-11 pr-4 py-2.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/35 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/40 focus:border-cyan-300/60 focus:bg-white/15 transition-all duration-200"
                        >
                    </div>
                    @error('password')
                        <p class="text-xs font-medium text-rose-300 mt-1 pl-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember Me & Extra options --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            wire:model="remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-white/20 bg-white/10 text-cyan-500 focus:ring-offset-0 focus:ring-1 focus:ring-cyan-400/50"
                        >
                        <span class="text-xs font-medium text-white/75">
                            Ingat saya
                        </span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-cyan-600 via-teal-600 to-emerald-600 hover:from-cyan-700 hover:via-teal-700 hover:to-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg hover:shadow-cyan-500/25 active:scale-[0.98] transition-all duration-300 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{-- Loading Spinner --}}
                        <svg wire:loading wire:target="authenticate" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        
                        <span wire:loading.remove wire:target="authenticate">
                            Masuk
                        </span>
                        <span wire:loading wire:target="authenticate">
                            Memproses...
                        </span>
                    </button>
                </div>

            </form>
        </div>

        {{-- Footer Copyright --}}
        <div class="mt-8 text-center">
            <p class="text-[0.7rem] text-white/40 tracking-wider">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>

    </div>
</div>
