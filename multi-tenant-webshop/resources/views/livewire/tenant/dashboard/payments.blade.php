<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-neutral-900 dark:text-white">Betalingen & Stripe</h1>
        <p class="mt-2 text-lg text-neutral-600 dark:text-neutral-400">Beheer je Stripe-koppeling om betalingen van klanten te ontvangen.</p>
    </div>

    <div class="max-w-3xl">
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-8">
                <div class="flex items-center gap-6 mb-8">
                    <div class="flex-shrink-0 h-16 w-16 bg-indigo-600 rounded-2xl flex items-center justify-center">
                        <svg class="h-10 w-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.945 9.373c0-.822-.686-1.127-1.745-1.127-1.74 0-3.52.55-4.832 1.3L6.37 6.47c1.545-1.01 3.865-1.585 5.86-1.585 3.52 0 5.62 1.764 5.62 4.545 0 4.672-6.423 5.34-6.423 8.083v.44h-4.04v-1.166c0-3.23 6.558-3.924 6.558-7.414zM11.905 21.66c-1.396 0-2.528-1.132-2.528-2.528 0-1.395 1.132-2.527 2.528-2.527 1.395 0 2.527 1.132 2.527 2.527 0 1.396-1.132 2.528-2.527 2.528z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">Stripe Connect</h2>
                        <p class="text-neutral-500 dark:text-neutral-400">Ontvang betalingen direct op je eigen Stripe-account.</p>
                    </div>
                </div>

                @if ($isConnected)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 dark:bg-emerald-900/20 dark:border-emerald-800">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 bg-emerald-100 rounded-full flex items-center justify-center dark:bg-emerald-800/40">
                                <flux:icon name="check" class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-emerald-900 dark:text-emerald-300">Gekoppeld</h3>
                                <p class="text-emerald-700 dark:text-emerald-400">Je webshop is verbonden met Stripe account ID: <code class="font-mono bg-emerald-100 px-1 rounded dark:bg-emerald-800/60">{{ $tenant->stripe_account_id }}</code></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-neutral-200 dark:border-neutral-700">
                        <p class="text-sm text-neutral-500">Wil je een ander account koppelen? Neem contact op met de support.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        <div class="bg-neutral-50 border border-neutral-200 rounded-xl p-6 dark:bg-neutral-900/40 dark:border-neutral-700">
                            <h3 class="font-bold mb-2">Waarom koppelen met Stripe?</h3>
                            <ul class="space-y-2 text-neutral-600 dark:text-neutral-400 text-sm">
                                <li class="flex items-start gap-2">
                                    <flux:icon name="check-circle" class="h-5 w-5 text-indigo-500 flex-shrink-0" />
                                    <span>Acceptatie van iDEAL, Creditcards, Bancontact en meer.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <flux:icon name="check-circle" class="h-5 w-5 text-indigo-500 flex-shrink-0" />
                                    <span>Betalingen worden direct naar jouw rekening gestort.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <flux:icon name="check-circle" class="h-5 w-5 text-indigo-500 flex-shrink-0" />
                                    <span>Veilige en betrouwbare afhandeling door Stripe.</span>
                                </li>
                            </ul>
                        </div>

                        <div class="flex flex-col gap-4">
                            <flux:button href="{{ route('stripe.connect') }}" variant="primary" size="lg" class="w-full sm:w-auto" icon="link">
                                Verbinden met Stripe
                            </flux:button>
                            <p class="text-xs text-neutral-500">Je wordt doorgestuurd naar Stripe om de koppeling te voltooien.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
