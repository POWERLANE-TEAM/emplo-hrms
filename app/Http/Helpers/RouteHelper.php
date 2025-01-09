<?php

namespace App\Http\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RouteHelper
{
    public static function getByReferrer(?Request $request = null): string
    {
        $request = $request ?? request();
        $requestReferer = $request->headers->get('referer');
        $referrer = parse_url($requestReferer, PHP_URL_PATH);

        return match (true) {
            Str::is('/employee/*', $referrer) => 'employee',
            Str::is('/admin/*', $referrer) => 'admin',
            default => '',
        };
    }

    public static function getByRequest(?Request $request = null): string
    {
        $request = $request ?? request();

        return match (true) {
            $request->is('employee/*') => 'employee',
            $request->is('admin/*') => 'admin',
            default => '',
        };
    }


    public static function validateModel($model, $value)
    {


        if (!is_subclass_of($model, 'Illuminate\Database\Eloquent\Model')) {
            throw new \InvalidArgumentException('The provided class is not a subclass of Illuminate\Database\Eloquent\Model');
        }

        if (!is_int($value) && !ctype_digit($value)) {
            abort(400);
        }



        $value = (int) $value;
        $primaryKey = (new $model)->getKeyName();
        $maxId = $model::max($primaryKey);

        if ($value < 1 || $value > $maxId) {
            abort(400);
        }

        return $validModel = $model::findOrFail($value);
    }
}
