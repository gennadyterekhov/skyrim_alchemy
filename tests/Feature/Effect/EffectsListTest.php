<?php

namespace Tests\Feature\Effect;

use App\Models\Effect;
use Tests\TestCase;

final class EffectsListTest extends TestCase
{
    public function test_can_get_effects_list(): void
    {
        $response = $this->get('/api/effects');

        $response->assertStatus(200);
        $responseData = json_decode($response->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertDatabaseCount(Effect::class, Effect::COUNT);
        $this->assertCount(Effect::COUNT, $responseData);
        $this->assertSerialization($responseData[0]);
    }

    private function assertSerialization(array $responseData): void
    {
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('text', $responseData);
        $this->assertArrayHasKey('magnitude', $responseData);
        $this->assertArrayHasKey('value', $responseData);
    }
}
