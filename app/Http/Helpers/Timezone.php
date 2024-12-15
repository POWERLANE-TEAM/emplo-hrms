<?php

namespace App\Http\Helpers;

class Timezone
{
    public static function get()
    {
        return session('userTimezone', config('app.server_timezone'));
    }
}
