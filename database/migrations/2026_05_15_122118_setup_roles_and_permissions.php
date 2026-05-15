<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create Permissions
        $permissions = [
            'view-chronos',
            'edit-chronos',
            'view-financial-projections',
            'manage-users',
            'view-all-invoices',
            'view-assigned-invoices',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        $owner = \Spatie\Permission\Models\Role::create(['name' => 'owner']);
        $owner->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        $admin = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view-chronos',
            'edit-chronos',
            'view-all-invoices',
        ]);

        $staff = \Spatie\Permission\Models\Role::create(['name' => 'staff']);
        $staff->givePermissionTo([
            'view-chronos',
            'view-assigned-invoices',
        ]);

        // Migrate Users
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            if ($user->role) {
                $user->assignRole($user->role);
            }
        }
    }

    public function down(): void
    {
        \DB::table('role_has_permissions')->delete();
        \DB::table('model_has_roles')->delete();
        \DB::table('model_has_permissions')->delete();
        \DB::table('roles')->delete();
        \DB::table('permissions')->delete();
    }
};
