<?php

namespace Tests\Feature\Effect;

use Tests\TestCase;

final class ShowEffectTest extends TestCase
{
    public function test_can_get_effect(): void
    {
        $response = $this->get('/api/effects/000ae723');

        $response->assertStatus(200);
        $responseData = json_decode($response->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertSerialization($responseData);
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
