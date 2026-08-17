<?php

namespace App\Livewire\Tenant\Settings;

use App\Models\Tenant\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class ComplianceSettings extends Component
{
    public bool $cookie_banner_enabled = true;

    public string $cookie_banner_text = 'Wij gebruiken cookies om uw ervaring te verbeteren. Door verder te gaan op onze website gaat u akkoord met ons gebruik van cookies.';

    public string $privacy_policy_content = '';

    public function mount()
    {
        $this->cookie_banner_enabled = (bool) (Setting::where('key', 'compliance_cookie_banner_enabled')->first()?->value ?? true);
        $this->cookie_banner_text = Setting::where('key', 'compliance_cookie_banner_text')->first()?->value ?? 'Wij gebruiken cookies om uw ervaring te verbeteren. Door verder te gaan op onze website gaat u akkoord met ons gebruik van cookies.';
        $this->privacy_policy_content = Setting::where('key', 'compliance_privacy_policy_content')->first()?->value ?? '';
    }

    public function generateTemplate()
    {
        $companyName = Setting::where('key', 'invoice_company_name')->first()?->value ?? config('app.name');
        $address = Setting::where('key', 'invoice_address')->first()?->value ?? '[Adres]';
        $email = Setting::where('key', 'invoice_email')->first()?->value ?? '[Email]';
        $vatNumber = Setting::where('key', 'invoice_vat_number')->first()?->value ?? '[BTW Nummer]';

        $template = "
# Privacybeleid voor {$companyName}

Bij {$companyName}, toegankelijk via onze webshop, is de privacy van onze bezoekers een van onze belangrijkste prioriteiten. Dit Privacybeleid bevat de soorten informatie die door {$companyName} worden verzameld en vastgelegd en hoe we deze gebruiken.

## Contactgegevens
**Bedrijfsnaam:** {$companyName}  
**Adres:** {$address}  
**E-mail:** {$email}  
**BTW-nummer:** {$vatNumber}

## Logbestanden
{$companyName} volgt een standaardprocedure voor het gebruik van logbestanden. Deze bestanden registreren bezoekers wanneer ze websites bezoeken. Alle hostingbedrijven doen dit en maken deel uit van de analyse van hostingdiensten.

## Cookies en Web Beacons
Net als elke andere website gebruikt {$companyName} 'cookies'. Deze cookies worden gebruikt om informatie op te slaan, waaronder de voorkeuren van bezoekers en de pagina's op de website die de bezoeker heeft bezocht of bekeken. De informatie wordt gebruikt om de gebruikerservaring te optimaliseren door onze webpagina-inhoud aan te passen op basis van het browsertype van de bezoeker en/of andere informatie.

## Privacybeleid van derden
Het privacybeleid van {$companyName} is niet van toepassing op andere adverteerders of websites. Daarom adviseren wij u om het respectievelijke privacybeleid van deze externe advertentieservers te raadplegen voor meer gedetailleerde informatie.

## Informatie over kinderen
Een ander deel van onze prioriteit is het toevoegen van bescherming voor kinderen tijdens het gebruik van internet. We moedigen ouders en voogden aan om hun online activiteiten te observeren, eraan deel te nemen en/of deze te controleren en te begeleiden.

## Alleen online privacybeleid
Dit privacybeleid is alleen van toepassing op onze online activiteiten en is geldig voor bezoekers van onze website met betrekking tot de informatie die zij hebben gedeeld en/of verzamelen in {$companyName}.

## Toestemming
Door onze website te gebruiken, stemt u hierbij in met ons privacybeleid en gaat u akkoord met de voorwaarden ervan.
        ";

        $this->privacy_policy_content = trim($template);

        $this->dispatch('privacy-policy-generated');
    }

    public function save()
    {
        Setting::updateOrCreate(['key' => 'compliance_cookie_banner_enabled'], ['value' => $this->cookie_banner_enabled]);
        Setting::updateOrCreate(['key' => 'compliance_cookie_banner_text'], ['value' => $this->cookie_banner_text]);
        Setting::updateOrCreate(['key' => 'compliance_privacy_policy_content'], ['value' => $this->privacy_policy_content]);

        session()->flash('message', 'Compliance instellingen succesvol bijgewerkt!');
    }

    public function render()
    {
        return view('livewire.tenant.settings.compliance-settings');
    }
}
