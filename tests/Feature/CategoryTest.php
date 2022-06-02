<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testGetCategories () {
        Category::factory(7)->create();

        $response = $this->get('/get-categories');

        $response->assertStatus(200)
                 ->assertJsonStructure()
                 ->assertJsonCount(7);
    }
}
