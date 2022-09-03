<?php

namespace App\Helper;

use Exception;

final class AssertHelper
{

    public static function assert(bool $condition, string $text = 'Assertion Error'): void
    {
        if (!$condition) {
            throw new Exception($text);
        }
    }
}
