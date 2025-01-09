<?php

namespace App\Http\Helpers;

use Carbon\Carbon;

class Timezone
{
    protected $timezone;

    public static function get()
    {
        $instance = new self();
        $instance->timezone = session('userTimezone', config('app.timezone'));
        return $instance;
    }

    public function withOffset()
    {
        $timezoneOffset = Carbon::now()->setTimezone($this->timezone)->format('P');
        return [$this->timezone, $timezoneOffset];
    }

    public function __toString()
    {
        return $this->timezone;
    }
}
