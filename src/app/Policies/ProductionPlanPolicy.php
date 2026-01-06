<?php

namespace App\Policies;

use App\Models\ProductionPlan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductionPlanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductionPlan $productionPlan): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role->name, ['System Administrator', 'Production Manager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductionPlan $productionPlan): bool
    {
        return in_array($user->role->name, ['System Administrator', 'Production Manager'])
            && $productionPlan->status === 'Draft';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductionPlan $productionPlan): bool
    {
        return in_array($user->role->name, ['System Administrator', 'Production Manager'])
            && $productionPlan->status === 'Draft';
    }

    /**
     * Determine whether the user can submit the model.
     */
    public function submit(User $user, ProductionPlan $productionPlan): bool
    {
        return in_array($user->role->name, ['System Administrator', 'Production Manager']);
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, ProductionPlan $productionPlan): bool
    {
        return in_array($user->role->name, ['System Administrator', 'Production Manager']);
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, ProductionPlan $productionPlan): bool
    {
        return in_array($user->role->name, ['System Administrator', 'Production Manager']);
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, ProductionPlan $productionPlan): bool
    {
        return in_array($user->role->name, ['System Administrator', 'Production Manager']);
    }
}