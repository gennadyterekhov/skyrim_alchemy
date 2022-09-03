<?php

namespace Tests\Feature\Ingredient;

use App\Models\Effect;
use App\Models\Ingredient;
use Tests\TestCase;

final class ShowIngredientTest extends TestCase
{
    public function test_can_get_ingredient(): void
    {
        $response = $this->get('/api/ingredients/00106e1b');

        $response->assertStatus(200);
        $responseData = json_decode($response->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertSerialization($responseData);
    }

    private function assertSerialization(array $responseData): void
    {
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('weight', $responseData);
        $this->assertArrayHasKey('value', $responseData);
        $this->assertIsArray($responseData['effect_1']);
        $this->assertEmbeddedEffectSerialization($responseData['effect_1']);

    }

    private function assertEmbeddedEffectSerialization(array $responseData): void
    {
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('text', $responseData);
        $this->assertArrayHasKey('magnitude', $responseData);
        $this->assertArrayHasKey('value', $responseData);
    }
}
