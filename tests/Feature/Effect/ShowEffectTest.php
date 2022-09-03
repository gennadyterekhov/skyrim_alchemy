<?php

namespace Tests\Feature\Effect;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowEffectTest extends TestCase
{
//    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_can_get_effect()
    {
        $response = $this->get('/api/effects/000ae723');

        $response->assertStatus(200);
        $responseData = json_decode($response->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('text', $responseData);
        $this->assertArrayHasKey('magnitude', $responseData);
        $this->assertArrayHasKey('value', $responseData);
    }

}
