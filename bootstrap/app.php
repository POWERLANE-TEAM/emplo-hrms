<?php

use App\Http\Middleware\Localization;
use App\Http\Middleware\SaveVisitedPage;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('employee')
                ->name('employee.')
                ->group(base_path('routes/employee.php'));

            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->append(\Spatie\Csp\AddCspHeaders::class);

        $middleware->alias([
            'save.page' => SaveVisitedPage::class,
            'localization' => Localization::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('employee/*')) {
                return 'employee/login';
            }
            if ($request->is('admin/*')) {
                return 'admin/login';
            }
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->is('employee/*')) {
                return 'employee/dashboard';
            }
            if ($request->is('admin/*')) {
                return 'admin/dashboard';
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
