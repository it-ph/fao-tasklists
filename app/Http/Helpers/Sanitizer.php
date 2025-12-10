<?php

namespace App\Http\Helpers;

use HTMLPurifier;
use HTMLPurifier_Config;

class Sanitizer {
    protected static $purifier;

    protected static function getPurifier(): HTMLPurifier
    {
        if(!static::$purifier) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed','');

            // customize allowed HTML globally here if needed
            // $config->set('HTML.Allowed', 'p,b,a[href],i,ul,li,br');

            static::$purifier = new HTMLPurifier($config);
        }

        return static::$purifier;
    }

    public static function cleanArray(array $input): array
    {
        $purifier = static::getPurifier();

        foreach ($input as $key => $value) {
            if(is_string($value)) {
                $input[$key] = $purifier->purify($value);
            } elseif (is_array($value)) {
                $input[$key] = static::cleanArray($value);
            }
        }

        return $input;
    }
}
