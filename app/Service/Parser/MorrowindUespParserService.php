<?php

namespace App\Service\Parser;

use DOMDocument;
use DOMNode;

/**
 * This is dirty, but it's a one-time hack to get data
 */
class MorrowindUespParserService extends BaseParser implements ParserInterface
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
        $effectsColumnIndex = 2;
        $valueColumnIndex = 3;
        $weightColumnIndex = 4;
        $currentTdIndex = 0;
        $currentIngredientName = '';

        foreach ($child->childNodes as $trChild) {
            if ($trChild->nodeName === self::TD_TAG) {
                if ($currentTdIndex === $idColumnIndex) {
                    if (str_contains($trChild->nodeValue, $currentIngredientName)) {
                        $currentIngredientName = $this->getNameFromTd($trChild->nodeValue);
                        $url = $this->getUrlFromXml($trChild->ownerDocument->saveXML($trChild));

                        if ($currentIngredientName === null) {
                            continue;
                        }
                        if ($url !== null) {
                            $this->resultArray['ingredients'][$currentIngredientName]['uesp_url'] = self::UESP_BASE_URL.$url;
                        }
                    }
                }

                if ($currentTdIndex === $effectsColumnIndex && $currentIngredientName !== null) {
                    $this->updateEffects($trChild->ownerDocument->saveXML($trChild));
                }
                if ($currentTdIndex === $valueColumnIndex && $currentIngredientName !== null) {
                    $this->resultArray['ingredients'][$currentIngredientName]['value'] = intval(($trChild->nodeValue));
                }
                if ($currentTdIndex === $weightColumnIndex && $currentIngredientName !== null) {
                    $this->resultArray['ingredients'][$currentIngredientName]['weight'] = floatval(($trChild->nodeValue));
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
        $regex = '@href=\".*?\"@m';
        $result = preg_match_all($regex, $saveXML, $matches);

        if ($result === false) {
            return null;
        }
        if (array_key_exists(0, $matches) && array_key_exists(0, $matches[0])) {
            $urlWithHref = $matches[0][0];

            $url = str_replace('href="', '', $urlWithHref);
            $url = str_replace('"', '', $url);

            return $url;
        }
        return null;
    }

    private function updateEffects($nodeValue): void
    {
        $nodeValue = trim($nodeValue);
        $nodeValue = str_replace('\n', '', $nodeValue);
        $nodeValue = str_replace("\n", '', $nodeValue);

        $regex = '@<li.*?<\/li>@m';
        $result = preg_match_all($regex, $nodeValue, $matches);

        if ($result === false) {
            return;
        }

        if (array_key_exists(0, $matches)) {
            $listItems = $matches[0];
            foreach ($listItems as $listItem) {
                $this->updateEffect($listItem);
            }
        }
    }

    private function updateEffect(string $listItem): void
    {
        $name = $this->getEffectNameFromListItem($listItem);
        if ($name === null) {
            return;
        }
        $type = $this->getEffectTypeFromListItem($listItem);
        $icon = $this->getEffectIconFromListItem($listItem);
        $url = $this->getEffectUrlFromListItem($listItem);

        $this->resultArray['effects'][$name]['type'] = $type;
        $this->resultArray['effects'][$name]['icon'] = $icon;
        $this->resultArray['effects'][$name]['uesp_url'] = self::UESP_BASE_URL.$url;
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

        if (array_key_exists(0, $matches)) {
            $nameDirty = $matches[0][0];

            $name = str_replace('<a href="', '', $nameDirty);
            $name = str_replace('"', '', $name);

            return $name;
        }
        return null;
    }

    private function getEffectNameFromListItem(string $listItem): ?string
    {
        $listItem = trim($listItem);
        $listItem = str_replace('\n', '', $listItem);
        $listItem = str_replace("\n", '', $listItem);
        $regex = '@<span style="display: block; padding-left: 20px;">.*?</span>@m';
        $result = preg_match_all($regex, $listItem, $matches);

        if ($result === false) {
            return null;
        }

        if (array_key_exists(0, $matches)) {
            $nameDirty = $matches[0][0];

            $name = str_replace('<span style="display: block; padding-left: 20px;">', '', $nameDirty);
            $name = str_replace('</span>', '', $name);
            $name = str_replace('<i>', '', $name);
            $name = str_replace('</i>', '', $name);

            return $name;
        }
        return null;
    }

    private function getEffectTypeFromListItem(string $listItem): string
    {
        $listItem = trim($listItem);
        $listItem = str_replace('\n', '', $listItem);
        $listItem = str_replace("\n", '', $listItem);
        $regex = '@<li class=\".*?\"@m';
        $result = preg_match_all($regex, $listItem, $matches);

        if ($result === false) {
            return 'unknown';
        }

        if (array_key_exists(0, $matches)) {
            $nameDirty = $matches[0][0];

            $name = str_replace('<li class="', '', $nameDirty);
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
        $regex = '@<img alt=\"\" src=\"\/\/.*?\"@m';
        $result = preg_match_all($regex, $listItem, $matches);

        if ($result === false) {
            return null;
        }

        if (array_key_exists(0, $matches)) {
            $nameDirty = $matches[0][0];

            $name = str_replace('<img alt="" src="//', '', $nameDirty);
            $name = str_replace('"', '', $name);
            $name = explode('/16px', $name)[0];

            return 'https://'.$name;
        }
        return null;
    }
}
