<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Project::class => \App\Policies\ProjectPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Tambahkan Gate untuk mengizinkan user edit project miliknya sendiri
        Gate::define('update', function (User $user, Project $project) {
            return $user->id === $project->user_id;
        });
    }
}
