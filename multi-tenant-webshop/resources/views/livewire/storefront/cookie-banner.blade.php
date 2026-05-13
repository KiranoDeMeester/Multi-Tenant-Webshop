<div x-data="{ 
        visible: false,
        init() {
            if (!localStorage.getItem('cookies_accepted')) {
                setTimeout(() => this.visible = true, 1000);
            }
        },
        accept() {
            localStorage.setItem('cookies_accepted', 'true');
            this.visible = false;
        }
    }" 
    x-show="visible" 
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-10"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-10"
    class="fixed bottom-6 left-6 right-6 md:left-auto md:max-w-md z-[100]"
    style="display: none;"
>
    @if($enabled)
        <div class="bg-black border-2 border-white/20 p-6 shadow-[0_20px_50px_rgba(0,0,0,0.5)] high-contrast-dark overflow-hidden relative group">
            <!-- Background accent -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
            
            <div class="relative flex flex-col gap-5">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-primary/10 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-primary">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-[0.2em] mb-1 text-white">{{ __('Cookie Voorkeuren') }}</h4>
                        <p class="text-[13px] leading-relaxed text-neutral-400 font-medium">
                            {{ $text }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button @click="accept" class="flex-1 bg-primary hover:bg-primary/90 text-white text-[11px] font-black uppercase tracking-widest py-3 px-6 transition-all active:scale-95">
                        {{ __('Accepteren') }}
                    </button>
                    <a href="{{ route('storefront.pages.privacy') }}" class="text-[10px] font-black uppercase tracking-widest text-neutral-500 hover:text-white transition-colors px-4 py-2 border border-white/10 hover:border-white/30">
                        {{ __('Details') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
