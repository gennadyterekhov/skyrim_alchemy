<?php

namespace App\Service;

use App\Models\Ingredient;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class IngredientService
{
    public static function findByIdOrFail(mixed $id): Ingredient
    {
        $ingredient = Ingredient::query()
            ->with('effect_1')
            ->with('effect_2')
            ->with('effect_3')
            ->with('effect_4')
            ->find($id);

        if (!$ingredient instanceof Ingredient) {
            throw new NotFoundHttpException();
        }

        return $ingredient;
    }

    /**
     * @return Ingredient[]
     */
    public static function list(): array
    {
        return Ingredient::with('effect_1')
            ->with('effect_2')
            ->with('effect_3')
            ->with('effect_4')
            ->get()->toArray();
    }
}
