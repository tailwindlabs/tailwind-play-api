<?php

namespace Tests\Feature;

use App\Models\Playground;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PlaygroundTest extends TestCase
{
    use RefreshDatabase;

    public function test_playground_can_be_retrieved()
    {
        $playground = Playground::factory()->create([
            'uuid' => 'abc123',
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
        ]);

        $response = $this->get('/api/playgrounds/abc123');

        $response->assertStatus(200);

        $response->assertJson([
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
        ]);
    }

    public function test_playground_can_be_created()
    {
        $response = $this->postJson('/api/playgrounds', [
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
        ]);

        $playground = Playground::find($response['id']);

        $this->assertEquals(10, Str::length($playground->uuid));
        $this->assertEquals('test html', $playground->html);
        $this->assertEquals('test css', $playground->css);
        $this->assertEquals('test config', $playground->config);
    }

    public function test_duplicate_playgrounds_are_reused()
    {
        $response = $this->postJson('/api/playgrounds', [
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
        ]);

        $response->assertStatus(201);
        $this->assertEquals(1, Playground::count());

        $response = $this->postJson('/api/playgrounds', [
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, Playground::count());
    }
}
