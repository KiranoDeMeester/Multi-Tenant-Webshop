<?php

namespace App\Console\Commands;

use App\Models\Landlord\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ZipArchive;

class TenantBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:backup {slug : The slug of the tenant to backup} {--path= : Custom output directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a standalone ZIP archive containing the tenant SQLite database and uploaded media files';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $slug = $this->argument('slug');
        $tenant = Tenant::whereHas('domains', function ($q) use ($slug) {
            $q->where('domain', 'like', "{$slug}.%");
        })->first();

        if (! $tenant) {
            $this->error("Tenant with slug '{$slug}' not found.");

            return 1;
        }

        $this->info("Creating backup for tenant: {$tenant->name} ({$tenant->slug})...");

        $dbPath = database_path("tenants/{$tenant->db_name}.sqlite");
        if (! File::exists($dbPath)) {
            // Check fallback for in-memory or custom paths
            $dbPath = database_path("tenants/{$slug}.sqlite");
        }

        $outputDir = $this->option('path') ?: storage_path('app/backups');
        if (! File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true, true);
        }

        $timestamp = now()->format('Y-m-d_His');
        $zipFilename = "backup_{$slug}_{$timestamp}.zip";
        $zipPath = $outputDir.DIRECTORY_SEPARATOR.$zipFilename;

        $zip = new ZipArchive;
        $opened = File::exists($zipPath)
            ? $zip->open($zipPath, ZipArchive::OVERWRITE)
            : $zip->open($zipPath, ZipArchive::CREATE);

        if ($opened !== true) {
            $this->error("Failed to create ZIP archive at: {$zipPath}");

            return 1;
        }

        // 1. Add SQLite Database
        if (File::exists($dbPath)) {
            $zip->addFile($dbPath, "database/{$slug}.sqlite");
            $this->line('  Added SQLite database: '.basename($dbPath));
        }

        // 2. Add Tenant Media (if exists)
        $mediaDir = public_path("media/{$tenant->id}");
        if (File::exists($mediaDir)) {
            $files = File::allFiles($mediaDir);
            foreach ($files as $file) {
                $relativePath = substr($file->getPathname(), strlen($mediaDir) + 1);
                $zip->addFile($file->getPathname(), "media/{$relativePath}");
            }
            $this->line('  Added '.count($files).' media files.');
        }

        // 3. Add manifest metadata JSON
        $manifest = [
            'tenant_id' => $tenant->id,
            'tenant_name' => $tenant->name,
            'slug' => $tenant->slug,
            'created_at' => $tenant->created_at?->toIso8601String(),
            'backup_created_at' => now()->toIso8601String(),
            'laravel_version' => app()->version(),
        ];
        $zip->addFromString('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));

        $zip->close();

        $this->info("Backup created successfully: {$zipPath} (".round(filesize($zipPath) / 1024, 2).' KB)');

        return 0;
    }
}
