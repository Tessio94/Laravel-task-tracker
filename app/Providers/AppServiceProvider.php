<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $loginRateLimitResponse = function(Request $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'message' => 'Too many login attempts. Please try again later.'
                    ],
                    429
                );
            }

            return back()->withErrors(
                 [
                    'email' => 'Too many login attempts. Please try again later.'
                 ]
            )->withInput($request->except('password'));
        };

        RateLimiter::for(
            'login',
            function(Request $request) use ($loginRateLimitResponse) {
                return [
                    Limit::perMinute(100)->by($request->ip())->response($loginRateLimitResponse),

                    Limit::perMinute(5)->by($request->input('email'))->response($loginRateLimitResponse)
                ];
            }
        );

        RateLimiter::for(
            'password-reset-request',
            function(Request $request) {
                return [
                    Limit::perHour(10)->by($request->ip()),

                    Limit::perHour(3)->by($request->input('email'))
                ];
            }
        );

        RateLimiter::for(
            'password-reset',
            function(Request $request) {
                return [
                    Limit::perHour(5)->by($request->ip()),

                    Limit::perHour(3)->by($request->input('email'))
                ];
            }
        );

        Password::defaults(function() {
            /** @var Application $app */
            $app = $this->app;

            if($app->isLocal()) {
                return Password::min(8);
            }

            return Password::min(8)
                ->mixedCase()
                ->uncompromised()
                ->letters()
                ->numbers()
                ->symbols();
        });

        DB::listen(function(QueryExecuted $query) {
            Log::info($query->sql, ['bindings' => $query->bindings, 'time' => $query->time]);
        });


    }
}
