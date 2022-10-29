<?php

namespace App\Service\Parser;

use DOMDocument;
use DOMNode;
use DOMNodeList;

/**
 * This is dirty, but it's a one-time hack to get data
 */
class OblivionUespParserService extends BaseParser implements ParserInterface
{
    private const UESP_BASE_URL = 'https://en.uesp.net';
    private const TR_TAG = 'tr';
    private const TD_TAG = 'td';
    private string $sourceDataString = '';
    private array $resultArray;
    private array $inputData;
    private array $inputNames;

    public function parse(string $sourceDataString, array $inputData = []): array
    {
        $this->resultArray = $inputData;
        $this->inputData = $inputData;
        $this->inputNames = $this->getInputNames();

        $this->sourceDataString = $sourceDataString;
        $this->sourceDataString = $this->purify($sourceDataString);

        return $this->parseIntoArray();
    }

    private function purify(string $sourceDataString): string
    {
        return $this->removeTags();
    }

    private function purifyString(string $sourceDataString): string
    {
        $this->sourceDataString = str_replace('\n', '', $this->sourceDataString);
        $this->sourceDataString = str_replace('\\n', '', $this->sourceDataString);
        $this->sourceDataString = str_replace("\n", '', $this->sourceDataString);

        $this->sourceDataString = str_replace('        ', ' ', $this->sourceDataString);
        $this->sourceDataString = str_replace('    ', ' ', $this->sourceDataString);
        $this->sourceDataString = str_replace('  ', ' ', $this->sourceDataString);
        $this->sourceDataString = str_replace('  ', ' ', $this->sourceDataString);
        $this->sourceDataString = str_replace('  ', ' ', $this->sourceDataString);
        $this->sourceDataString = str_replace('  ', ' ', $this->sourceDataString);

        return $this->sourceDataString;
    }

    private function removeTags()
    {
        $invalidTags = [
            'wbr'
        ];
        foreach ($invalidTags as $invalidTag) {
            $this->sourceDataString = $this->removeTag($invalidTag);
        }
        return $this->sourceDataString;
    }

    private function removeTag(string $invalidTag): string
    {
        $this->sourceDataString = str_replace("<$invalidTag/>", '', $this->sourceDataString);

        $this->sourceDataString = str_replace("<$invalidTag>", '', $this->sourceDataString);
        $this->sourceDataString = str_replace("</$invalidTag>", '', $this->sourceDataString);

        return $this->sourceDataString;
    }

    private function getIdFromTd(string $nodeValue): array
    {
        $name = '';
        $id = '';

        foreach ($this->inputNames as $inputName) {
            $position = strpos($nodeValue, $inputName);
            if ($position !== false) {
                $id = $this->getIdFromTdString($nodeValue, $inputName);
                return [$id, $inputName];
            }
        }

        return [$id, $name];
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
        $dom->loadHTML($this->sourceDataString);
        $tableDomElement = $dom->getElementById('main-table');

        /** @var DOMNodeList $childNodes */
        $childNodes = $tableDomElement->childNodes;
        $tableLinesCount = $childNodes->count();

        for ($i = 3; $i < $tableLinesCount - 2; $i += 4) {
            /** @var DOMNode $child */
            $child = $childNodes->item($i);
            $this->addTrToArray($child, $childNodes->item($i + 2));
        }

        return $this->resultArray;
    }

    private function addTrToArray(DOMNode $child, DOMNode $nextChild): void
    {
        $childNodes = [...$child->childNodes, ...$nextChild->childNodes];

        $idColumnIndex = 1;
        $effects1ColumnIndex = 3;
        $effects2ColumnIndex = 4;
        $effects3ColumnIndex = 5;
        $effects4ColumnIndex = 6;

        $valueColumnIndex = 7;
        $weightColumnIndex = 8;
        $harvestProbabilityColumnIndex = 9;

        $currentTdIndex = 0;
        $currentIngredientName = '';

        foreach ($childNodes as $trChild) {
            if ($trChild->nodeName === self::TD_TAG) {
                if ($currentTdIndex === $idColumnIndex) {
                    if (str_contains($trChild->nodeValue, $currentIngredientName)) {
                        $currentIngredientName = $this->getNameFromTd($trChild->nodeValue);

                        $url = $this->getUrlFromXml($trChild->ownerDocument->saveXML($trChild));
                        $id = $this->getIdFromXml($trChild->ownerDocument->saveXML($trChild));

                        if ($currentIngredientName === null) {
                            continue;
                        }
                        if ($url !== null) {
                            $this->resultArray['ingredients'][$currentIngredientName]['uesp_url'] = self::UESP_BASE_URL.$url;
                        }
                        if ($id !== null) {
                            $this->resultArray['ingredients'][$currentIngredientName]['id'] = $id;
                        }
                    }
                }

                if ($currentTdIndex >= 3 && $currentTdIndex <= 6 && $currentIngredientName !== null) {
                    $this->updateEffects(trim($trChild->nodeValue), $trChild->ownerDocument->saveXML($trChild));
                }
                if ($currentTdIndex === $valueColumnIndex && $currentIngredientName !== null) {
                    $this->resultArray['ingredients'][$currentIngredientName]['value'] = intval(($trChild->nodeValue));
                }
                if ($currentTdIndex === $weightColumnIndex && $currentIngredientName !== null) {
                    $this->resultArray['ingredients'][$currentIngredientName]['weight'] = floatval(($trChild->nodeValue));
                }
                if ($currentTdIndex === $harvestProbabilityColumnIndex && $currentIngredientName !== null) {
                    $this->resultArray['ingredients'][$currentIngredientName]['harvest_probability'] = (($trChild->nodeValue === 'N/A') ? null: intval($trChild->nodeValue));
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

    private function getInputNames(): array
    {
        $names = [];
        foreach ($this->inputData['ingredients'] as $ingredient) {
            $names[] = $ingredient['name'];
        }
        return $names;
    }

    private function getNameFromTd($nodeValue): ?string
    {
        foreach ($this->inputNames as $inputName) {
            $position = strpos($nodeValue, $inputName);
            if ($position !== false) {
                return $inputName;
            }
        }

        return null;
    }

    private function getUrlFromXml($saveXML): ?string
    {
        $regex = '@<a href=\".*?\"@m';
        $result = preg_match_all($regex, $saveXML, $matches);

        if ($result === false) {
            return null;
        }
        if (array_key_exists(0, $matches) && array_key_exists(0, $matches[0])) {
            $urlWithHref = $matches[0][0];

            $url = str_replace('<a href="', '', $urlWithHref);
            $url = str_replace('"', '', $url);

            return $url;
        }
        return null;
    }

    private function updateEffects(string $effectName, string $nodeValue): void
    {
        $nodeValue = trim($nodeValue);
        $nodeValue = str_replace('\n', '', $nodeValue);
        $nodeValue = str_replace("\n", '', $nodeValue);
        $effectName = trim($effectName);
        $effectName = str_replace(' ', '', $effectName);

        if (!$effectName) {
            return;
        }

        $type = $this->getEffectTypeFromListItem($nodeValue);
        $icon = $this->getEffectIconFromListItem($nodeValue);
        $url = $this->getEffectUrlFromListItem($nodeValue);

        $this->resultArray['effects'][$effectName]['type'] = $type;
        $this->resultArray['effects'][$effectName]['icon'] = $icon;
        $this->resultArray['effects'][$effectName]['uesp_url'] = self::UESP_BASE_URL.$url;
    }

    private function getEffectUrlFromListItem(string $listItem): ?string
    {
        $listItem = trim($listItem);
        $listItem = str_replace('\n', '', $listItem);
        $listItem = str_replace("\n", '', $listItem);
        $regex = '@<a href=\".*?\"@m';
        $result = preg_match_all($regex, $listItem, $matches);

        if ($result === false) {
            return null;
        }

        if (array_key_exists(0, $matches) && array_key_exists(0, $matches[0])) {
            $nameDirty = $matches[0][0];

            $name = str_replace('<a href="', '', $nameDirty);
            $name = str_replace('"', '', $name);

            return $name;
        }
        return null;
    }

    private function getEffectTypeFromListItem(string $listItem): string
    {
        $listItem = trim($listItem);
        $listItem = str_replace('\n', '', $listItem);
        $listItem = str_replace("\n", '', $listItem);
        $regex = '@<td class=\".*?\"@m';
        $result = preg_match_all($regex, $listItem, $matches);

        if ($result === false) {
            return 'unknown';
        }

        if (array_key_exists(0, $matches)) {
            $nameDirty = $matches[0][0];

            $name = str_replace('<td class="', '', $nameDirty);
            $name = str_replace('"', '', $name);

            return $name === 'EffectPos' ? 'positive' : 'negative';
        }
        return 'unknown';
    }

    private function getEffectIconFromListItem(string $listItem): ?string
    {
        $listItem = trim($listItem);
        $listItem = str_replace('\n', '', $listItem);
        $listItem = str_replace("\n", '', $listItem);
        $regex = '@<img .*? src=\"\/\/.*?\"@m';
        $result = preg_match_all($regex, $listItem, $matches);

        if ($result === false) {
            return null;
        }

        if (array_key_exists(0, $matches) && array_key_exists(0, $matches[0])) {
            $nameDirty = $matches[0][0];
            $name = explode('src="//', $nameDirty)[1];

            $name = str_replace('"', '', $name);
            if (str_contains($name, '/16px')) {
                $name = explode('/16px', $name)[0];
            }

            return 'https://'.$name;
        }
        return null;
    }

    private function getIdFromXml($saveXML): ?string
    {
        return $this->getIdRefFromXml($saveXML) . $this->getIdCaseFromXml($saveXML);
    }

    private function getIdCaseFromXml($saveXML): ?string
    {
        $listItem = $saveXML;

        $listItem = trim($listItem);
        $listItem = str_replace('\n', '', $listItem);
        $listItem = str_replace("\n", '', $listItem);
        $regex = '@idcase\">.+?<@m';
        $result = preg_match_all($regex, $listItem, $matches);

        if ($result === false) {
            return null;
        }

        if (array_key_exists(0, $matches) && array_key_exists(0, $matches[0])) {
            $nameDirty = $matches[0][0];
            $name = str_replace('idcase">', '', $nameDirty);
            $name = str_replace('<', '', $name);

            return $name;
        }
        return null;
    }

    private function getIdRefFromXml($saveXML): ?string
    {
        $listItem = $saveXML;

        $listItem = trim($listItem);
        $listItem = str_replace('\n', '', $listItem);
        $listItem = str_replace("\n", '', $listItem);
        $regex = '@idref\">.+?<@m';
        $result = preg_match_all($regex, $listItem, $matches);

        if ($result === false) {
            return null;
        }

        if (array_key_exists(0, $matches) && array_key_exists(0, $matches[0])) {
            $nameDirty = $matches[0][0];
            $name = str_replace('idref">', '', $nameDirty);

            $name = str_replace('<', '', $name);

            return $name;
        }
        return null;
    }
}
