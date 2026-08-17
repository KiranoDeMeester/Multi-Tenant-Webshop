<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public bool $isCreating = false;

    public bool $isEditing = false;

    public string $tenant_id = '';

    public string $name = '';

    public string $domain = '';

    public function createTenant()
    {
        $this->reset(['tenant_id', 'name', 'domain']);
        $this->isCreating = true;
        $this->isEditing = false;
        \Flux::modal('tenant-modal')->show();
    }

    public function editTenant(Tenant $tenant)
    {
        $this->tenant_id = $tenant->id;
        $this->name = $tenant->name;
        $this->domain = $tenant->primary_domain?->domain ?? '';
        $this->isCreating = false;
        $this->isEditing = true;
        \Flux::modal('tenant-modal')->show();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:landlord.domains,domain'.($this->isEditing ? ','.Domain::where('domain', $this->domain)->first()?->id : ''),
        ]);

        if ($this->isCreating) {
            $dbName = 'tenant_'.Str::slug($this->name).'_'.Str::random(5);
            $tenant = Tenant::create([
                'name' => $this->name,
                'db_name' => $dbName,
            ]);

            Domain::create([
                'tenant_id' => $tenant->id,
                'domain' => $this->domain,
                'is_primary' => true,
            ]);

            // Create and migrate database
            app(TenantManager::class)->setTenant($tenant);
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\Tenant\\TenantDatabaseSeeder',
                '--force' => true,
            ]);

            session()->flash('message', 'Webshop succesvol aangemaakt.');
        } else {
            $tenant = Tenant::findOrFail($this->tenant_id);
            $tenant->update(['name' => $this->name]);

            if ($this->domain) {
                Domain::updateOrCreate(
                    ['tenant_id' => $tenant->id, 'is_primary' => true],
                    ['domain' => $this->domain]
                );
            }

            session()->flash('message', 'Webshop succesvol bijgewerkt.');
        }

        \Flux::modal('tenant-modal')->close();
        $this->resetPage();
    }

    public function toggleActive($tenantId)
    {
        $tenant = Tenant::withTrashed()->findOrFail($tenantId);
        $tenant->update(['is_active' => ! $tenant->is_active]);
        session()->flash('message', $tenant->is_active ? 'Webshop geactiveerd.' : 'Webshop gedeactiveerd.');
    }

    public function softDelete($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $tenant->delete();
        session()->flash('message', 'Webshop naar prullenbak verplaatst (Soft Delete).');
    }

    public function restoreTenant($tenantId)
    {
        $tenant = Tenant::withTrashed()->findOrFail($tenantId);
        $tenant->restore();
        session()->flash('message', 'Webshop hersteld.');
    }

    public function hardDelete($tenantId)
    {
        $tenant = Tenant::withTrashed()->findOrFail($tenantId);

        // Optionally delete the SQLite file
        if ($tenant->db_name) {
            $dbPath = database_path('tenants/'.$tenant->db_name.'.sqlite');
            if (file_exists($dbPath)) {
                @unlink($dbPath);
            }
        }

        $tenant->forceDelete();
        session()->flash('message', 'Webshop permanent verwijderd.');
    }

    public function render()
    {
        return view('livewire.admin.tenants.index', [
            'tenants' => Tenant::withTrashed()->with('domains')->latest()->paginate(10),
            'totalTenants' => Tenant::withTrashed()->count(),
            'totalDomains' => Domain::count(),
        ]);
    }
}
