<?php

namespace App\Helpers;

use App\Models\Setting;

if (! function_exists('getSetting')) {
    function getSetting(string $key, $default = null): mixed
    {
        return optional(Setting::where('key', $key)->first())->value ?? $default;
    }
}

if (! function_exists('setSetting')) {
    function setSetting(string $key, $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
