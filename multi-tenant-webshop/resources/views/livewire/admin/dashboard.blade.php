<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-tighter">Platform Dashboard</h1>
            <p class="text-sm text-neutral-500">Welkom bij het Kirano Multi-Tenant Platform beheer.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-black text-white p-6 rounded-3xl shadow-xl high-contrast-dark relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
            <div class="relative z-10">
                <div class="text-xs font-black uppercase tracking-[0.2em] text-neutral-400 mb-1">Actieve Shops</div>
                <div class="text-4xl font-black mb-2">{{ $totalTenants }}</div>
                <flux:button href="{{ route('admin.tenants') }}" variant="ghost" size="sm" class="!text-white !p-0 hover:!bg-transparent group/link">
                    Beheer shops 
                    <flux:icon name="arrow-right" size="sm" class="ml-1 transition-transform group-hover/link:translate-x-1" />
                </flux:button>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-3xl border-2 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
            <div class="text-xs font-black uppercase tracking-[0.2em] text-neutral-500 mb-1">Gekoppelde Domeinen</div>
            <div class="text-4xl font-black mb-2">{{ $totalDomains }}</div>
            <div class="text-xs font-bold text-neutral-400 uppercase tracking-widest">Alle subdomeinen actief</div>
        </div>

        <div class="bg-indigo-600 text-white p-6 rounded-3xl shadow-xl relative overflow-hidden group">
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full blur-2xl -ml-12 -mb-12"></div>
            <div class="relative z-10">
                <div class="text-xs font-black uppercase tracking-[0.2em] text-indigo-200 mb-1">Platform Status</div>
                <div class="text-2xl font-black mb-2 uppercase italic tracking-tighter italic">Operational</div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest">All systems normal</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Tenants -->
        <div class="bg-white dark:bg-neutral-800 rounded-3xl border-2 border-black p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black uppercase tracking-tight">Nieuwste Webshops</h3>
                <flux:button variant="ghost" size="sm" href="{{ route('admin.tenants') }}">Bekijk alles</flux:button>
            </div>
            
            <div class="space-y-4">
                @foreach($recentTenants as $tenant)
                    <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900/50 rounded-2xl border border-neutral-100 dark:border-neutral-700 transition-all hover:border-black dark:hover:border-white">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-black text-white rounded-xl flex items-center justify-center font-black text-xs uppercase">
                                {{ substr($tenant->name, 0, 2) }}
                            </div>
                            <div>
                                <div class="font-black text-sm uppercase tracking-tight">{{ $tenant->name }}</div>
                                <div class="text-[10px] text-neutral-500 font-bold uppercase tracking-widest">{{ $tenant->primary_domain?->domain }}</div>
                            </div>
                        </div>
                        @if($tenant->primary_domain)
                            @php
                                $port = request()->getPort();
                                $redirectDomain = $tenant->primary_domain->domain;
                                if ($port && !in_array($port, [80, 443])) {
                                    $redirectDomain = "{$redirectDomain}:{$port}";
                                }
                                $protocol = str_contains(config('app.url'), 'https') ? 'https' : 'http';
                                $url = "{$protocol}://{$redirectDomain}";
                            @endphp
                            <flux:button variant="ghost" size="sm" icon="arrow-right" href="{{ $url }}" target="_blank" />
                        @else
                            <flux:button variant="ghost" size="sm" icon="arrow-right" disabled />
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-neutral-100 dark:bg-neutral-900 rounded-3xl p-8 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-black uppercase tracking-tight mb-2 text-neutral-900 dark:text-white">Systeem Informatie</h3>
                <p class="text-sm text-neutral-500 mb-8 font-medium">Overzicht van de huidige platform configuratie en resources.</p>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-1">Laravel Versie</div>
                        <div class="font-bold">v13.x</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-1">Livewire Versie</div>
                        <div class="font-bold">v4.x</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-1">Database</div>
                        <div class="font-bold uppercase">MySQL + SQLite</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-1">Omgeving</div>
                        <div class="font-bold text-indigo-600 uppercase">Development</div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white dark:bg-neutral-800 rounded-full flex items-center justify-center border border-neutral-200 dark:border-neutral-700 shadow-sm">
                        <flux:icon name="cpu-chip" size="sm" />
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-neutral-400 uppercase tracking-widest">Platform Engine</div>
                        <div class="text-xs font-bold">Kirano Enterprise v1.0</div>
                    </div>
                </div>
                <flux:badge color="neutral" size="sm">STABLE</flux:badge>
            </div>
        </div>
    </div>
</div>
