<?php

namespace App\Service\Parser;

use DOMDocument;
use DOMNode;
use DOMNodeList;

/**
 * This is dirty, but it's a one-time hack to get data
 */
class SkyrimUespParserService extends BaseParser implements ParserInterface
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
        dump(__METHOD__);

        $dom = new DOMDocument;
        $dom->loadHTML($this->sourceDataString);
        $tableDomElement = $dom->getElementById('main-table');

        /** @var DOMNodeList $childNodes */
        $childNodes = $tableDomElement->childNodes;
        $tbody = $childNodes->item(1);
        /** @var DOMNodeList $childNodes */
        $childNodes = $tbody->childNodes;
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

        $iconColumnIndex = 0;

        $idColumnIndex = 1;
        $textColumnIndex = 2;

        $effects1ColumnIndex = 3;
        $effects2ColumnIndex = 4;
        $effects3ColumnIndex = 5;
        $effects4ColumnIndex = 6;

        $valueColumnIndex = 7;
        $weightColumnIndex = 8;

        $currentTdIndex = 0;
        $currentIngredientName = '';
        $iconUrl = null;
        foreach ($childNodes as $trChild) {
            if ($trChild->nodeName === self::TD_TAG) {
                if ($currentTdIndex === $iconColumnIndex) {
                    $iconUrl = $this->getIconUrl($trChild);
                }
                if ($currentTdIndex === $idColumnIndex) {
                    if (str_contains($trChild->nodeValue, $currentIngredientName)) {
                        $currentIngredientName = $this->getNameFromTd($trChild->nodeValue);

                        $url = $this->getUrlFromXml($trChild->ownerDocument->saveXML($trChild));
                        $id = $this->getIdFromXml($trChild, $trChild->ownerDocument->saveXML($trChild));

                        if (!$currentIngredientName) {
                            continue;
                        }
                        $this->resultArray['ingredients'][$currentIngredientName]['name'] = $currentIngredientName;
                        if ($url !== null) {
                            $this->resultArray['ingredients'][$currentIngredientName]['uesp_url'] = $url;
                        }
                        if ($id !== null && !array_key_exists('id', $this->resultArray['ingredients'][$currentIngredientName])) {
                            $this->resultArray['ingredients'][$currentIngredientName]['id'] = $id;
                        }
                    }
                }
                if ($currentIngredientName && !array_key_exists($currentIngredientName, $this->resultArray['ingredients'])) {
                    $this->resultArray['ingredients'][$currentIngredientName] = [
                        'id' => null,
                        'name' => null,
                        'effects' => [],
                        'text' => null,
                        'icon' => null,
                    ];
                }
                if ($currentIngredientName) {
                    $this->resultArray['ingredients'][$currentIngredientName]['icon'] = $iconUrl;
                }

                if ($currentTdIndex >= 3 && $currentTdIndex <= 6 && $currentIngredientName !== null) {
                    if (!array_key_exists('effects', $this->resultArray['ingredients'][$currentIngredientName]) || count($this->resultArray['ingredients'][$currentIngredientName]['effects']) !== 4) {
                        $this->setEffects($currentIngredientName, $trChild);
                    }
                    $this->updateEffects($trChild);
                }
                if ($currentTdIndex === $textColumnIndex && $currentIngredientName) {
                    $this->resultArray['ingredients'][$currentIngredientName]['text'] = ($trChild->nodeValue);
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

    private function getInputNames(): array
    {
        $names = [];
        foreach ($this->inputData['ingredients'] as $name => $ingredient) {
            $names[] = $name;
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

    private function updateEffects($trChild): void
    {
        $xml = $trChild->ownerDocument->saveXML($trChild);
        $nodeValue = $xml;
        $nodeValue = trim($nodeValue);
        $nodeValue = str_replace('\n', '', $nodeValue);
        $nodeValue = str_replace("\n", '', $nodeValue);

        $effectName = $this->getEffectNameFromDomNode($trChild);

        if (!$effectName) {
            return;
        }
        $type = $this->getEffectTypeFromListItem($nodeValue);
        $icon = $this->getEffectIconFromListItem($trChild, $nodeValue);
        $url = $this->getEffectUrlFromListItem($nodeValue);

        $this->resultArray['effects'][$effectName]['type'] = $type;
        $this->resultArray['effects'][$effectName]['icon'] = $icon;
        $this->resultArray['effects'][$effectName]['uesp_url'] = $url;
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

    private function getEffectIconFromListItem(DOMNode $trChild, string $listItem): ?string
    {
        $imgTag = $trChild->childNodes[0]->childNodes[0];
        $sources = $imgTag->attributes->getNamedItem('srcset');
        $sourcesStrs = explode('//', $sources->value);

        foreach ($sourcesStrs as $iconUrlWithScale) {
            $iconUrl = explode(' ', $iconUrlWithScale);
            if ($iconUrl[0]) {
                return 'https://'.$iconUrl[0];
            }
        }
        return null;
    }

    private function getIdFromXml(DOMNode $trChild, string $saveXML): ?string
    {
        return $trChild->childNodes[4]->nodeValue ?? null;
    }

    private function getIconUrl(DOMNode $trChild): ?string
    {
        /** @var DOMNodeList $tdChildren */
        $tdChildren = $trChild->childNodes;

        /** @var DOMNode $aTag */
        $imgTag = $tdChildren[0]->childNodes[0];

        $sources = $imgTag->attributes->getNamedItem('srcset');
        $sourcesStrs = explode('//', $sources->value);

        foreach ($sourcesStrs as $iconUrlWithScale) {
            $iconUrl = explode(' ', $iconUrlWithScale);
            if ($iconUrl[0]) {
                return 'https://'.$iconUrl[0];
            }
        }
        return null;
    }

    private function setEffects($currentIngredientName, mixed $trChild): void
    {
        $effectName = $this->getEffectNameFromDomNode($trChild);

        if (!$effectName) {
            return;
        }
        $this->resultArray['ingredients'][$currentIngredientName]['effects'][] = $effectName;
    }

    private function getEffectNameFromDomNode($trChild): ?string
    {
        return $trChild->childNodes[2]->nodeValue ?? null;
    }
}
