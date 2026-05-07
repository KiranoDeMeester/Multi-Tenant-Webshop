<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-2">
        <h1 class="text-2xl font-semibold tracking-tight">Backend Dashboard</h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400">Log in om je webshop te beheren</p>
    </div>

    <form wire:submit="login" class="flex flex-col gap-4">
        <flux:input wire:model="email" label="E-mailadres" type="email" placeholder="owner@example.com" required autofocus />
        
        <flux:input wire:model="password" label="Wachtwoord" type="password" placeholder="********" required />

        <div class="flex items-center justify-between">
            <flux:checkbox label="Onthoud mij" />
        </div>

        <flux:button type="submit" variant="primary" class="w-full">Inloggen</flux:button>
    </form>
</div>
