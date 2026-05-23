<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ActivityLog;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamManager extends Component
{
    public $search = '';

    // Modals Control
    public $showPermissionsModal = false;
    public $showSuspendModal = false;
    public $showEditModal = false;

    // Permissions State
    public $selectedUser = null;
    public $selectedUserRole = '';
    public $selectedUserPermissions = []; // format: ['permission-name' => true/false]

    // Suspend State
    public $userToSuspend = null;

    // Edit Profile State
    public $editingUser = null;
    public $editingName = '';
    public $editingEmail = '';
    public $editingRole = '';
    public $editingIsActive = false;
    public $editingPassword = '';
    public $showPassword = false;
    public $copied = false;
    public $selectedUserLogs = [];

    // All available permissions in the system
    public $allPermissions = [];

    public function mount()
    {
        $this->allPermissions = Permission::all()->pluck('name')->toArray();
    }

    public function openPermissions($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->selectedUserRole = $this->selectedUser->role;
        
        // Fetch permissions for this user
        $this->selectedUserPermissions = [];
        foreach ($this->allPermissions as $perm) {
            $this->selectedUserPermissions[$perm] = $this->selectedUser->hasPermissionTo($perm);
        }

        $this->showPermissionsModal = true;
    }

    public function savePermissions()
    {
        if (!$this->selectedUser) return;

        // Safety: Prevent Admin/Owner from lock-out or removing their own role/permissions
        if ($this->selectedUser->id === auth()->id() && $this->selectedUserRole !== auth()->user()->role) {
            $this->dispatch('notify', ['message' => 'You cannot change your own role.', 'type' => 'danger']);
            return;
        }

        $this->validate([
            'selectedUserRole' => 'required|in:owner,admin,staff'
        ]);

        // Update basic role column
        $this->selectedUser->update([
            'role' => $this->selectedUserRole
        ]);

        // Sync roles via Spatie HasRoles
        $this->selectedUser->syncRoles([$this->selectedUserRole]);

        // Sync permissions
        $permissionsToSync = [];
        foreach ($this->selectedUserPermissions as $permName => $enabled) {
            if ($enabled) {
                $permissionsToSync[] = $permName;
            }
        }
        $this->selectedUser->syncPermissions($permissionsToSync);

        ActivityLog::log('updated_user_permissions', "Updated role ({$this->selectedUserRole}) and permissions for team member: {$this->selectedUser->name}", $this->selectedUser);

        $this->showPermissionsModal = false;
        $this->dispatch('notify', [
            'message' => app()->getLocale() == 'en' ? 'Spatie permissions updated successfully.' : 'Hak akses Spatie berhasil diperbarui.',
            'type' => 'success'
        ]);
        $this->dispatch('refreshLucide');
    }

    public function confirmSuspend($userId)
    {
        $this->userToSuspend = User::findOrFail($userId);
        
        if ($this->userToSuspend->id === auth()->id()) {
            $this->dispatch('notify', [
                'message' => app()->getLocale() == 'en' ? 'You cannot suspend your own account.' : 'Anda tidak dapat menonaktifkan akun sendiri.',
                'type' => 'danger'
            ]);
            return;
        }

        $this->showSuspendModal = true;
    }

    public function toggleSuspend()
    {
        if (!$this->userToSuspend) return;

        $newStatus = !$this->userToSuspend->is_active;
        $this->userToSuspend->update([
            'is_active' => $newStatus
        ]);

        $action = $newStatus ? 'restored_user' : 'suspended_user';
        $desc = $newStatus 
            ? "Restored access for team member: {$this->userToSuspend->name}"
            : "Suspended access for team member: {$this->userToSuspend->name}";

        ActivityLog::log($action, $desc, $this->userToSuspend);

        $msg = $newStatus
            ? (app()->getLocale() == 'en' ? 'Operative access restored successfully.' : 'Akses staf berhasil dipulihkan.')
            : (app()->getLocale() == 'en' ? 'Operative access suspended successfully.' : 'Akses staf berhasil ditangguhkan.');

        $this->showSuspendModal = false;
        $this->dispatch('notify', [
            'message' => $msg,
            'type' => $newStatus ? 'success' : 'warning'
        ]);
        $this->userToSuspend = null;
        $this->dispatch('refreshLucide');
    }

    public function openEditModal($userId)
    {
        $this->editingUser = User::findOrFail($userId);
        $this->editingName = $this->editingUser->name;
        $this->editingEmail = $this->editingUser->email;
        $this->editingRole = $this->editingUser->role;
        $this->editingIsActive = (bool) $this->editingUser->is_active;
        $this->editingPassword = '';
        $this->showPassword = false;
        $this->copied = false;

        // Fetch activity logs
        $this->selectedUserLogs = $this->editingUser->activityLogs()
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($log) => [
                'desc' => $log->description,
                'time' => $log->created_at->diffForHumans()
            ])
            ->toArray();

        $this->showEditModal = true;
    }

    public function generatePassword()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+';
        $generated = '';
        for ($i = 0; $i < 16; $i++) {
            $generated .= $chars[rand(0, strlen($chars) - 1)];
        }
        $this->editingPassword = $generated;
        $this->showPassword = true;
        $this->copied = false;
    }

    public function copyPassword()
    {
        $this->copied = true;
    }

    public function saveEdit()
    {
        if (!$this->editingUser) return;

        $this->validate([
            'editingName' => 'required|string|max:255',
            'editingEmail' => 'required|string|email|max:255|unique:users,email,' . $this->editingUser->id,
            'editingRole' => 'required|in:owner,admin,staff',
            'editingIsActive' => 'boolean'
        ]);

        // Safety checks
        if ($this->editingUser->id === auth()->id()) {
            if ($this->editingRole !== auth()->user()->role) {
                $this->dispatch('notify', ['message' => 'You cannot change your own role.', 'type' => 'danger']);
                return;
            }
            if (!$this->editingIsActive) {
                $this->dispatch('notify', ['message' => 'You cannot deactivate your own account.', 'type' => 'danger']);
                return;
            }
        }

        $this->editingUser->update([
            'name' => $this->editingName,
            'email' => $this->editingEmail,
            'role' => $this->editingRole,
            'is_active' => $this->editingIsActive
        ]);

        // Sync role in Spatie
        $this->editingUser->syncRoles([$this->editingRole]);

        ActivityLog::log('updated_user', "Updated profile/role for user: {$this->editingUser->name}", $this->editingUser);

        if ($this->editingPassword) {
            $this->editingUser->update([
                'password' => Hash::make($this->editingPassword),
                'last_password_change_at' => now()
            ]);
            ActivityLog::log('updated_user_password', "Overruled password for user: {$this->editingUser->name}", $this->editingUser);
        }

        $this->showEditModal = false;
        $this->dispatch('notify', [
            'message' => app()->getLocale() == 'en' ? 'Operative details updated.' : 'Detail data pelaksana berhasil diperbarui.',
            'type' => 'success'
        ]);
        $this->dispatch('refreshLucide');
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            $this->dispatch('notify', ['message' => 'You cannot delete yourself.', 'type' => 'danger']);
            return;
        }

        $name = $user->name;
        $user->delete();

        ActivityLog::log('deleted_user', "Removed user account: {$name}");

        $this->showEditModal = false;
        $this->dispatch('notify', [
            'message' => app()->getLocale() == 'en' ? 'User successfully deleted.' : 'User berhasil dihapus.',
            'type' => 'success'
        ]);
        $this->dispatch('refreshLucide');
    }

    public function render()
    {
        $users = User::withCount('invoices')
            ->with(['activityLogs' => fn($q) => $q->latest()->limit(5)])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('role', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        return view('livewire.team-manager', compact('users'));
    }
}
