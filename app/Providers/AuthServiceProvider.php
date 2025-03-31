<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Section;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('edit-course', function (User $user, Course $course) {
            return is_null($course->published_at);
        });

        Gate::define('learn-course', function (User $user, Course $course) {
            return Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists();
        });

    }
}
