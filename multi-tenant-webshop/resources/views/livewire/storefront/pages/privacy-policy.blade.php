<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
        <h1 class="text-4xl font-black uppercase tracking-tighter mb-4">{{ __('Privacy Policy') }}</h1>
        <div class="h-1.5 w-24 bg-primary"></div>
    </div>

    <div class="prose prose-neutral max-w-none prose-headings:font-black prose-headings:uppercase prose-headings:tracking-tight prose-a:text-primary">
        @if(Str::contains($content, '#'))
            {!! Str::markdown($content) !!}
        @else
            {!! nl2br(e($content)) !!}
        @endif
    </div>

    <div class="mt-16 pt-8 border-t border-neutral-100 flex justify-between items-center text-xs font-bold text-neutral-400 uppercase tracking-widest">
        <span>{{ __('Laatst bijgewerkt') }}: {{ date('d-m-Y') }}</span>
        <a href="{{ route('storefront.products.index') }}" class="hover:text-primary transition-colors">{{ __('Terug naar winkelen') }}</a>
    </div>
</div>
