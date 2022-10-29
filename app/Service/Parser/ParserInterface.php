<?php

namespace App\Service\Parser;

interface ParserInterface
{
    public function parse(string $sourceDataString, array $inputData = []): array;

}
