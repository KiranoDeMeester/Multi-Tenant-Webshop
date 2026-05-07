<div class="flex items-center justify-center min-h-screen bg-neutral-50 dark:bg-neutral-900">
    <div class="text-center p-8 bg-white dark:bg-neutral-800 rounded-3xl shadow-xl border border-neutral-100 dark:border-neutral-700 max-w-lg">
        <div class="inline-flex items-center justify-center p-4 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl mb-6">
            <flux:icon name="shopping-cart" class="h-10 w-10 text-indigo-600 dark:text-indigo-400" />
        </div>
        <h1 class="text-4xl font-black text-neutral-900 dark:text-white mb-4">Welkom bij onze webshop!</h1>
        <p class="text-lg text-neutral-600 dark:text-neutral-400 mb-8">Onze winkel is momenteel in opbouw. Kom binnenkort terug voor de beste producten!</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <flux:button variant="primary" icon="user" href="/login">Klant Login</flux:button>
            <flux:button variant="ghost" icon="squares-2x2" href="/dashboard/login">Winkel Dashboard</flux:button>
        </div>
    </div>
</div>
