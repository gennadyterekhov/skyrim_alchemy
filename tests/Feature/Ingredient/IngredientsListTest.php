<?php

namespace Tests\Feature\Ingredient;

use App\Models\Ingredient;
use Tests\TestCase;

final class IngredientsListTest extends TestCase
{
    public function test_can_get_ingredients_list(): void
    {
        $response = $this->get('/api/ingredients');

        $response->assertStatus(200);
        $responseData = json_decode($response->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertDatabaseCount(Ingredient::class, Ingredient::COUNT);
        $this->assertCount(Ingredient::COUNT, $responseData);
        $this->assertSerialization($responseData[0]);
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
