<?php

namespace App\Service;

use App\Models\Effect;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class EffectService
{

    public static function findByIdOrFail(mixed $id): Effect
    {
        $effect = Effect::query()->find($id);

        if (!$effect instanceof Effect) {
            throw new NotFoundHttpException();
        }

        return $effect;
    }

    /**
     * @return Effect[]
     */
    public static function list(): array
    {
        return Effect::all()->toArray();
    }
}
