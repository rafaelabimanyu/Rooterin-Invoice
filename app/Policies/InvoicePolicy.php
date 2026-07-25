<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'owner' || $user->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        $createdBy = $invoice->created_by ?? null;
        if (is_null($createdBy)) {
            return true;
        }
        return $user->id === $createdBy 
            && $invoice->created_at >= now()->subHours(24);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        $createdBy = $invoice->created_by ?? null;
        if (is_null($createdBy)) {
            return true;
        }
        return $user->id === $createdBy 
            && $invoice->created_at >= now()->subHours(24);
    }
}
