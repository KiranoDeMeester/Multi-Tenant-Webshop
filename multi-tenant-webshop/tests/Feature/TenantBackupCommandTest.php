<?php

use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ZipArchive;

beforeEach(function () {
    $this->migrateLandlord();

    $this->tenant = Tenant::create([
        'name' => 'Backup Test Shop',
        'slug' => 'backupshop',
        'db_name' => 'backupshop_db',
    ]);

    Domain::create([
        'tenant_id' => $this->tenant->id,
        'domain' => 'backupshop.localhost',
        'is_primary' => true,
    ]);

    // Create a temporary sqlite file for testing
    $this->tempDbPath = database_path('tenants/backupshop_db.sqlite');
    if (! File::exists(dirname($this->tempDbPath))) {
        File::makeDirectory(dirname($this->tempDbPath), 0755, true);
    }
    File::put($this->tempDbPath, 'SQLite format 3 dummy header');

    $this->backupDir = storage_path('app/testing_backups');
    if (File::exists($this->backupDir)) {
        File::deleteDirectory($this->backupDir);
    }
});

afterEach(function () {
    if (File::exists($this->tempDbPath)) {
        File::delete($this->tempDbPath);
    }
    if (File::exists($this->backupDir)) {
        File::deleteDirectory($this->backupDir);
    }
});

test('tenant backup command generates valid zip archive with database and manifest', function () {
    $exitCode = Artisan::call('tenant:backup', [
        'slug' => 'backupshop',
        '--path' => $this->backupDir,
    ]);

    expect($exitCode)->toBe(0);

    $files = File::files($this->backupDir);
    expect(count($files))->toBe(1);

    $zipFile = $files[0]->getPathname();
    expect($zipFile)->toEndWith('.zip');

    $zip = new ZipArchive;
    expect($zip->open($zipFile))->toBeTrue();

    // Verify manifest.json exists inside zip
    $manifestContent = $zip->getFromName('manifest.json');
    expect($manifestContent)->not->toBeFalse();

    $manifest = json_decode($manifestContent, true);
    expect($manifest['slug'])->toBe('backupshop');
    expect($manifest['tenant_name'])->toBe('Backup Test Shop');

    $zip->close();
});
