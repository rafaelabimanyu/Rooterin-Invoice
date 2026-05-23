<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\TeamManager;

class TeamManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup basic Spatie roles & permissions if not already present
        // Since Spatie permission uses DB, let's create a test permission
        Permission::findOrCreate('view-chronos');
        Permission::findOrCreate('edit-chronos');
        Role::findOrCreate('owner');
        Role::findOrCreate('admin');
        Role::findOrCreate('staff');
    }

    public function test_authorized_user_can_access_team_management_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSeeLivewire(TeamManager::class);
    }

    public function test_unauthorized_user_cannot_access_team_management_page(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        $user->assignRole('staff');

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_user_can_update_roles_and_permissions_via_livewire(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');
        
        $staff = User::factory()->create(['role' => 'staff']);
        $staff->assignRole('staff');

        Livewire::actingAs($admin)
            ->test(TeamManager::class)
            ->call('openPermissions', $staff->id)
            ->assertSet('selectedUserRole', 'staff')
            ->set('selectedUserRole', 'admin')
            ->set('selectedUserPermissions.view-chronos', true)
            ->call('savePermissions')
            ->assertHasNoErrors()
            ->assertSet('showPermissionsModal', false);

        $staff->refresh();
        $this->assertEquals('admin', $staff->role);
        $this->assertTrue($staff->hasRole('admin'));
        $this->assertTrue($staff->hasPermissionTo('view-chronos'));
    }

    public function test_user_can_suspend_and_restore_operatives_via_livewire(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        $staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $staff->assignRole('staff');

        // Suspend
        Livewire::actingAs($admin)
            ->test(TeamManager::class)
            ->call('confirmSuspend', $staff->id)
            ->call('toggleSuspend')
            ->assertHasNoErrors()
            ->assertSet('showSuspendModal', false);

        $staff->refresh();
        $this->assertFalse((bool) $staff->is_active);

        // Restore
        Livewire::actingAs($admin)
            ->test(TeamManager::class)
            ->call('confirmSuspend', $staff->id)
            ->call('toggleSuspend')
            ->assertHasNoErrors()
            ->assertSet('showSuspendModal', false);

        $staff->refresh();
        $this->assertTrue((bool) $staff->is_active);
    }
}
