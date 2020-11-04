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
            'version' => '1',
        ]);
    }

    public function test_playground_can_be_created()
    {
        $response = $this->postJson('/api/playgrounds', [
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
            'version' => '1',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
            'version' => '1',
        ]);

        $playground = Playground::find($response['id']);

        $this->assertEquals(10, Str::length($playground->uuid));
        $this->assertEquals('test html', $playground->html);
        $this->assertEquals('test css', $playground->css);
        $this->assertEquals('test config', $playground->config);
    }

    public function test_playground_can_be_created_using_version_2()
    {
        $response = $this->postJson('/api/playgrounds', [
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
            'version' => '2',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
            'version' => '2',
        ]);

        $playground = Playground::find($response['id']);

        $this->assertEquals(10, Str::length($playground->uuid));
        $this->assertEquals('test html', $playground->html);
        $this->assertEquals('test css', $playground->css);
        $this->assertEquals('test config', $playground->config);
    }

    public function test_other_versions_are_invalid()
    {
        $response = $this->postJson('/api/playgrounds', [
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
            'version' => '3',
        ]);

        $response->assertStatus(422);
    }

    public function test_duplicate_playgrounds_are_reused()
    {
        $response = $this->postJson('/api/playgrounds', [
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
            'version' => '1',
        ]);

        $response->assertStatus(201);
        $this->assertEquals(1, Playground::count());

        $response = $this->postJson('/api/playgrounds', [
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
            'version' => '1',
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, Playground::count());
    }

    public function test_using_a_different_version_is_not_a_duplicate()
    {
        $response = $this->postJson('/api/playgrounds', [
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
            'version' => '1',
        ]);

        $response->assertStatus(201);
        $this->assertEquals(1, Playground::count());

        $response = $this->postJson('/api/playgrounds', [
            'html' => 'test html',
            'css' => 'test css',
            'config' => 'test config',
            'version' => '2',
        ]);

        $response->assertStatus(201);
        $this->assertEquals(2, Playground::count());
    }
}
