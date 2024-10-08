<?php

namespace App\Http\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChooseGuard
{
    public static function getByReferrer(): string
    {
        $request_referer = request()->headers->get('referer');
        $referrer = parse_url($request_referer, PHP_URL_PATH);

        return match (true) {
            Str::is('/employee/*', $referrer) => 'employee',
            Str::is('/admin/*', $referrer) => 'admin',
            default => 'web',
        };
    }

    public static function getByRequest(Request $request = null): string
    {
        $request = $request ?? request();

        return match (true) {
            $request->is('employee/*') => 'employee',
            $request->is('admin/*') => 'admin',
            default => 'web',
        };
    }
}
