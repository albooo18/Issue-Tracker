<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function update(User $user, Project $project): bool
    {
        return (int) $project->owner_id === (int) $user->id;
    }

    public function delete(User $user, Project $project): bool
    {
        return (int) $project->owner_id === (int) $user->id;
    }
}
