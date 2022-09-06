<?php

namespace App\Service;

use App\Models\Effect;
use App\Models\Ingredient;
use Illuminate\Http\Request;

final class SearchService
{
    public static function search(Request $request): array
    {
        $searchQuery = $request->input('search', 'Search...');
        $searchQuery = strtolower($searchQuery);

        $data = [
            'effects' => self::searchEffects($searchQuery),
            'ingredients' => self::searchIngredients($searchQuery),
        ];

        return $data;
    }

    private static function searchEffects(string $searchQuery): array
    {
        return Effect::query()->whereRaw(
            'LOWER(id) LIKE :searchQuery
            OR LOWER(name) LIKE :searchQuery
            OR LOWER(text) LIKE :searchQuery',
            ['searchQuery' => "%$searchQuery%",]
        )->orderByRaw(
            'CASE WHEN LOWER(name) LIKE :searchQuery  THEN 1
             WHEN LOWER(text) LIKE :searchQuery THEN 2
             WHEN LOWER(id) LIKE :searchQuery THEN 3
             ELSE 4 END
             ASC',
        )->get()->toArray();
    }

    private static function searchIngredients(string $searchQuery): array
    {
        return Ingredient::query()
            ->with('effect_1')
            ->with('effect_2')
            ->with('effect_3')
            ->with('effect_4')
            ->whereRaw(
                'LOWER(id) LIKE :searchQuery OR LOWER(name) LIKE :searchQuery',
                ['searchQuery' => "%$searchQuery%",]
            )->orderByRaw(
                'CASE WHEN LOWER(name) LIKE :searchQuery  THEN 1
             WHEN LOWER(id) LIKE :searchQuery THEN 2
             ELSE 3 END
             ASC',
            )->get()->toArray();
    }
}
