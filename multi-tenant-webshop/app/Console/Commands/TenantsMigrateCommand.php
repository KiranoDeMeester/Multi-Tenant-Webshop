<?php

namespace App\Console\Commands;

use App\Models\Landlord\Tenant;
use App\Services\TenantManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TenantsMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:migrate {--fresh : Whether to run migrate:fresh} {--seed : Whether to seed the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for all tenant databases';

    /**
     * Execute the console command.
     */
    public function handle(TenantManager $tenantManager)
    {
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->info('No tenants found to migrate.');
            return;
        }

        $command = $this->option('fresh') ? 'migrate:fresh' : 'migrate';

        foreach ($tenants as $tenant) {
            $this->info("Migrating tenant: {$tenant->name} ({$tenant->db_name})");

            // Switch to tenant connection
            $tenantManager->setTenant($tenant);

            // Run migrations for this tenant
            Artisan::call($command, [
                '--path' => 'database/migrations/tenant',
                '--force' => true,
                '--database' => 'tenant', // Ensure it uses the dynamic 'tenant' connection
            ]);

            $this->info(Artisan::output());

            if ($this->option('seed')) {
                $this->info("Seeding tenant: {$tenant->name}");
                Artisan::call('db:seed', [
                    '--force' => true,
                    '--database' => 'tenant',
                ]);
                $this->info(Artisan::output());
            }

            $this->newLine();
        }

        $this->info('All tenant migrations completed.');
    }
}
