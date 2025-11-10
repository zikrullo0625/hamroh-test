<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class SmsService
{
    public function sendCode($number): void
    {
        $code = rand(100000, 999999);

        Cache::put($number, $code, now()->addMinutes(10));
        //Sms send logic
    }
}
