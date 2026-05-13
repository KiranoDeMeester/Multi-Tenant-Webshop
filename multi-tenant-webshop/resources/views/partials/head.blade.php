<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@php
    $tenantManager = app(\App\Services\TenantManager::class);
    $currentTenant = $tenantManager->getTenant();
    
    $globalMetaTitle = $currentTenant ? (\App\Models\Tenant\Setting::where('key', 'shop_meta_title')->first()?->value ?? $currentTenant->name) : config('app.name');
    $globalMetaDescription = $currentTenant ? (\App\Models\Tenant\Setting::where('key', 'shop_meta_description')->first()?->value ?? '') : '';
    
    $displayTitle = $meta_title ?? $title ?? $globalMetaTitle;
    $displayDescription = $meta_description ?? $globalMetaDescription;
@endphp

<title>{{ $displayTitle }}</title>
<meta name="description" content="{{ $displayDescription }}" />

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ $displayTitle }}" />
<meta property="og:description" content="{{ $displayDescription }}" />

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image" />
<meta property="twitter:title" content="{{ $displayTitle }}" />
<meta property="twitter:description" content="{{ $displayDescription }}" />

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
