<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use App\Services\BackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatabaseBackupTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;
    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and users
        $this->owner = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_active' => 1,
        ]);

        $this->staff = User::factory()->create([
            'role' => User::ROLE_STAFF,
            'is_active' => 1,
        ]);
    }

    /**
     * Test that Owner can access the backup page.
     */
    public function test_owner_can_access_backup_page()
    {
        $response = $this->actingAs($this->owner)
            ->get(route('backup.index'));

        $response->assertStatus(200);
        $response->assertSee('Pencadangan dan Ekspor Database');
    }

    /**
     * Test that Staff is forbidden from accessing the backup page.
     */
    public function test_staff_cannot_access_backup_page()
    {
        $response = $this->actingAs($this->staff)
            ->get(route('backup.index'));

        $response->assertStatus(403);
    }

    /**
     * Test that Owner can update backup schedule settings.
     */
    public function test_owner_can_update_backup_settings()
    {
        $response = $this->actingAs($this->owner)
            ->post(route('backup.update-settings'), [
                'backup_auto_status' => 'on',
                'backup_auto_frequency' => 'weekly',
                'backup_auto_time' => '04:00',
            ]);

        $response->assertRedirect(route('backup.index'));
        $this->assertEquals('on', Setting::get('backup_auto_status'));
        $this->assertEquals('weekly', Setting::get('backup_auto_frequency'));
        $this->assertEquals('04:00', Setting::get('backup_auto_time'));
    }

    /**
     * Test that Staff cannot update backup settings.
     */
    public function test_staff_cannot_update_backup_settings()
    {
        $response = $this->actingAs($this->staff)
            ->post(route('backup.update-settings'), [
                'backup_auto_status' => 'on',
                'backup_auto_frequency' => 'weekly',
                'backup_auto_time' => '04:00',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test manual backup export download.
     */
    public function test_owner_can_download_manual_backup()
    {
        $response = $this->actingAs($this->owner)
            ->post(route('backup.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/zip');
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment; filename=jnj_backup_'));
    }

    /**
     * Test that Staff cannot download backup.
     */
    public function test_staff_cannot_download_backup()
    {
        $response = $this->actingAs($this->staff)
            ->post(route('backup.export'));

        $response->assertStatus(403);
    }

    /**
     * Test that the Artisan backup command runs successfully.
     */
    public function test_artisan_backup_command_runs_successfully()
    {
        // Execute Artisan command
        $exitCode = Artisan::call('db:backup-auto');
        $this->assertEquals(0, $exitCode);

        // Check that a backup file was generated in the automated backup folder
        $files = glob(storage_path('app/backups/automated/*.zip'));
        $this->assertNotEmpty($files);

        // Cleanup generated test files
        foreach ($files as $file) {
            @unlink($file);
        }
    }
}
