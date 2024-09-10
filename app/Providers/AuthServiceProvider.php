<?php

namespace App\Providers;

use App\Models\Report;
use App\Models\User;
use App\Policies\UserPolicy;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Passport::ignoreRoutes();
        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::personalAccessTokensExpireIn(Carbon::now()->addMonths(6));

        Gate::before(function ($user) {
            return $user->role_id == 1;
        });

        Gate::define('teacher_set_mark', function (User $user, Report $report) {
            return Auth::user()->teacher_id === $report->teacherToSubject->teacher_id;
        });

        Gate::define('view_download_report', function (User $user, Report $report) {
            return (Auth::user()->teacher_id === $report->teacherToSubject->teacher_id)
                    || (Auth::user()->student_id === $report->student_id);
        });
    }
}
