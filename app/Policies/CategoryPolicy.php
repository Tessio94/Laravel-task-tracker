<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Determine whether the user can manage the model.
     */
    public function manage(User $user, Category $category): bool
    {
        return $category->user()->is($user);
    }
}
