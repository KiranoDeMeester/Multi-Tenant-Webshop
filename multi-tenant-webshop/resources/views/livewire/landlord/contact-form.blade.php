<div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-8 shadow-sm">
    <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-2">Hulp of vragen nodig?</h3>
    <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-6">Stuur ons een bericht en ons supportteam neemt binnen 24 uur contact met je op.</p>

    @if ($success)
        <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 mb-6">
            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">Bericht succesvol verzonden!</p>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">Bedankt voor je bericht. We nemen zo snel mogelijk contact met je op.</p>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-4">
        <div>
            <label for="name" class="block text-xs font-semibold text-neutral-750 dark:text-neutral-300 uppercase tracking-wider mb-1">Naam</label>
            <input type="text" id="name" wire:model.defer="name" class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror" placeholder="Je volledige naam">
            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-neutral-750 dark:text-neutral-300 uppercase tracking-wider mb-1">E-mailadres</label>
            <input type="email" id="email" wire:model.defer="email" class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror" placeholder="naam@voorbeeld.com">
            @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="message" class="block text-xs font-semibold text-neutral-750 dark:text-neutral-300 uppercase tracking-wider mb-1">Bericht</label>
            <textarea id="message" wire:model.defer="message" rows="4" class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 px-4 py-2.5 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('message') border-red-500 @enderror" placeholder="Hoe kunnen we je helpen?"></textarea>
            @error('message') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-100 text-white dark:text-neutral-950 font-semibold px-5 py-3 rounded-xl transition text-sm">
            Verstuur Bericht
        </button>
    </form>
</div>
