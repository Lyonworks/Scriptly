<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    // Hanya pemilik project yang boleh lihat/edit/delete
    public function view(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    public function update(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    public function delete(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }
}
