<?php

namespace App\Service;

use App\Models\Effect;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class ExportService
{
    public static function exportJson(Request $request): array
    {
        return self::dbToArray();
    }

    private static function dbToArray(): array
    {
        $effects = Effect::all()->toArray();
        $effects = self::populateWithIngredients($effects);

        $ingredients = Ingredient::all()->makeVisible([
            'effect_1_id',
            'effect_2_id',
            'effect_3_id',
            'effect_4_id',
        ])->toArray();

        $data = [
            'effects' => self::indexData($effects),
            'ingredients' => self::indexData($ingredients),
        ];

        return $data;
    }

    private static function indexData(array $data): array
    {
        $indexedData = [];
        foreach ($data as $item) {
            if (!array_key_exists('id', $item)) {
                throw new UnprocessableEntityHttpException('Item has no id ' . json_encode($item));
            }
            $indexedData[$item['id']] = $item;
        }

        return $indexedData;
    }

    private static function populateWithIngredients(array $effects): array
    {
        foreach ($effects as &$effectArray) {
            $id = $effectArray['id'];
            $effectArray['ingredients'] = [
                [], [], [], [],
            ];
            for ($i = 0; $i < 4; ++$i) {
                $ingredientsWithThisEffectInIthPlace = IngredientService::listByEffect($id, $i + 1);
                $ingredientIds = array_map(
                    static fn (array $ingredient) => $ingredient['id'],
                    $ingredientsWithThisEffectInIthPlace
                );
                $effectArray['ingredients'][$i] = $ingredientIds;
            }
        }
        return $effects;
    }
}
