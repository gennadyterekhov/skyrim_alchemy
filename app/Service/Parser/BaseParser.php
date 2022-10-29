<?php

namespace App\Service\Parser;

class BaseParser implements ParserInterface
{

    public function parse(string $sourceDataString, array $inputData = []): array
    {
        return [];
    }
}
