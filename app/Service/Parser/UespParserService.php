<?php

namespace App\Service\Parser;

use DOMDocument;
use DOMNode;

class UespParserService
{
    private const UESP_RAW_FILE = '/app/parse/uesp.html';
    private const TR_TAG = 'tr';
    private const TD_TAG = 'td';
    private string $rawDataString = '';
    private array $resultArray;
    private array $hogartData;
    private array $hogartNames;

    public function parse(string $rawDataString, array $hogartData = []): array
    {
        $this->resultArray = ['ingredients' => [], 'effects' => []];
        $this->hogartData = $hogartData;
        $this->hogartNames = $this->getHogartNames();

        $this->rawDataString = $rawDataString;
        $this->rawDataString = $this->purify($rawDataString);

        $this->saveRawFile();

        return $this->parseIntoArray();
    }

    private function purify(string $rawDataString): string
    {
        return $this->removeTags();
    }

    private function purifyString(string $rawDataString): string
    {
        $rawDataString = str_replace('\n', '', $rawDataString);
        $rawDataString = str_replace('\\n', '', $rawDataString);
        $rawDataString = str_replace("\n", '', $rawDataString);

        $rawDataString = str_replace('        ', ' ', $rawDataString);
        $rawDataString = str_replace('    ', ' ', $rawDataString);
        $rawDataString = str_replace('  ', ' ', $rawDataString);
        $rawDataString = str_replace('  ', ' ', $rawDataString);
        $rawDataString = str_replace('  ', ' ', $rawDataString);
        $rawDataString = str_replace('  ', ' ', $rawDataString);

        return $rawDataString;
    }

    private function removeTags()
    {
        $invalidTags = [
            'wbr'
        ];
        foreach ($invalidTags as $invalidTag) {
            $this->rawDataString = $this->removeTag($invalidTag);
        }
        return $this->rawDataString;
    }

    private function removeTag(string $invalidTag): string
    {
        $this->rawDataString = str_replace("<$invalidTag/>", '', $this->rawDataString);

        $this->rawDataString = str_replace("<$invalidTag>", '', $this->rawDataString);
        $this->rawDataString = str_replace("</$invalidTag>", '', $this->rawDataString);

        return $this->rawDataString;
    }

    private function saveRawFile()
    {
        file_put_contents(storage_path() . self::UESP_RAW_FILE, $this->rawDataString);
    }

    private function getIdFromTd(string $nodeValue): array
    {
        $name = '';
        $id = '';

        foreach ($this->hogartNames as $hogartName) {
            $position = strpos($nodeValue, $hogartName);
            if ($position !== false) {
                $id = $this->getIdFromTdString($nodeValue, $hogartName);
                $name = $hogartName;
                return [$id, $name];
            }
        }

        return [$id, $name];
    }

    private function getHogartNames(): array
    {
        $names = [];
        foreach ($this->hogartData['ingredients'] as $effect) {
            $names[] = $effect['name'];
        }
        return $names;
    }

    private function getIdFromTdString(string $nodeValue, string $hogartName): string
    {
        $nodeValue = str_replace($hogartName, '', $nodeValue);
        $nodeValue = str_replace(' ', '', $nodeValue);

        return $nodeValue;
    }

    private function parseIntoArray(): array
    {
        $dom = new DOMDocument;
        $dom->loadHTML($this->rawDataString);
        $tableDomElement = $dom->getElementById('main-table');

        /** @var DOMNode $child */
        foreach ($tableDomElement->childNodes as $child) {
            if ($child->nodeName === self::TR_TAG) {
                $this->addTrToArray($child);
            }
        }
        return $this->resultArray;
    }

    private function addTrToArray(DOMNode $child): void
    {
        $idColumnIndex = 0;
        $harvestProbabilityColumnIndex = 5;
        $currentTdIndex = 0;
        $currentIngredientId = '000';
        foreach ($child->childNodes as $trChild) {
            if ($trChild->nodeName === self::TD_TAG) {
                if ($currentTdIndex === $idColumnIndex) {
                    list($currentIngredientId, $name) = $this->getIdFromTd($this->purifyString($trChild->nodeValue));
                    $this->resultArray['ingredients'][$currentIngredientId] = [
                        'id' => $currentIngredientId,'name' => $name
                    ];
                }
                if ($currentTdIndex === $harvestProbabilityColumnIndex) {
                    $this->resultArray['ingredients'][$currentIngredientId]['harvest_probability'] = $this->getHarvestProbabilityFromTd($this->purifyString($trChild->nodeValue));
                }
                ++$currentTdIndex;
            }
        }
    }

    private function getHarvestProbabilityFromTd(string $harvestProbability): ?int
    {
        if ($harvestProbability === 'N/A') {
            return null;
        }

        $harvestProbability = str_replace('*', '', $harvestProbability);
        if (str_contains($harvestProbability, '/')) {
            $harvestProbability = explode('/', $harvestProbability)[0];
        }

        return intval($harvestProbability);
    }
}
