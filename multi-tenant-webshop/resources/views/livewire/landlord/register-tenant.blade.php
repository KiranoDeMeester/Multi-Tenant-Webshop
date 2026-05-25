<div class="w-full max-w-xl">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex h-12 w-12 bg-indigo-600 rounded-2xl items-center justify-center text-white font-extrabold text-2xl shadow-lg shadow-indigo-500/20 mb-3">
                S
            </div>
            <h1 class="text-2xl font-black tracking-tight">Zet je webshop op</h1>
            <p class="text-sm text-neutral-500 mt-1">Vul de gegevens in om je winkel te configureren.</p>
        </div>

        @if (!$isPaid)
            <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900 rounded-3xl p-6 text-center space-y-4 shadow-sm">
                <div class="h-12 w-12 bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center text-red-600 mx-auto">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-red-900 dark:text-red-400">Toegang Geweigerd</h3>
                <p class="text-sm text-red-755 dark:text-red-305">{{ $errorMessage }}</p>
                <a href="{{ route('home') }}" class="inline-block bg-neutral-900 hover:bg-neutral-800 text-white font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                    Terug naar de homepage
                </a>
            </div>
        @else
            <!-- Onboarding Form -->
            <div class="bg-white dark:bg-neutral-900 rounded-3xl border border-neutral-200 dark:border-neutral-800 p-8 shadow-sm">
                
                @if (session()->has('error'))
                    <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 rounded-xl p-4 mb-6">
                        <p class="text-sm text-red-850 dark:text-red-400 font-semibold">{{ session('error') }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="register" class="space-y-6">
                    
                    <!-- Shop Information -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">1. Winkelgegevens</h3>
                        
                        <div>
                            <label for="shop_name" class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider mb-1">Winkelnaam</label>
                            <input type="text" id="shop_name" wire:model.defer="shop_name" class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('shop_name') border-red-500 @enderror" placeholder="Mijn Geweldige Winkel">
                            @error('shop_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="subdomain" class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider mb-1">Gewenst Subdomein</label>
                            <div class="flex items-center">
                                <input type="text" id="subdomain" wire:model.defer="subdomain" class="w-full rounded-l-xl border-y border-l border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('subdomain') border-red-500 @enderror" placeholder="mijnwinkel">
                                <span class="bg-neutral-100 dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 px-4 py-2.5 text-sm text-neutral-500 rounded-r-xl font-medium">
                                    .{{ config('app.central_domain', 'localhost') }}
                                </span>
                            </div>
                            <p class="text-xs text-neutral-400 mt-1 font-medium">Dit wordt de link naar je webshop.</p>
                            @error('subdomain') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <hr class="border-neutral-200 dark:border-neutral-800">

                    <!-- Admin User Information -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">2. Beheerder Account</h3>
                        
                        <div>
                            <label for="admin_name" class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider mb-1">Volledige Naam</label>
                            <input type="text" id="admin_name" wire:model.defer="admin_name" class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('admin_name') border-red-500 @enderror" placeholder="Winkel Eigenaar">
                            @error('admin_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="admin_email" class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider mb-1">E-mailadres</label>
                            <input type="email" id="admin_email" wire:model.defer="admin_email" class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('admin_email') border-red-500 @enderror" placeholder="beheerder@voorbeeld.com">
                            @error('admin_email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="admin_password" class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider mb-1">Wachtwoord</label>
                            <input type="password" id="admin_password" wire:model.defer="admin_password" class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('admin_password') border-red-500 @enderror" placeholder="••••••••">
                            @error('admin_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-5 py-3 rounded-xl transition text-sm shadow-lg shadow-indigo-500/20 active:scale-95 flex items-center justify-center gap-2">
                        <span>Winkel Aanmaken & Starten</span>
                        <div wire:loading class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
                    </button>
                </form>
            </div>
        @endif
    </div>
