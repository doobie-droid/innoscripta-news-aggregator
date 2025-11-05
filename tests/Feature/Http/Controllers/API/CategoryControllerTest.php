<?php

namespace Tests\Feature;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    public function test_it_returns_all_categories_ordered_by_name()
    {
        Category::factory()->withName('Technology')->create();
        Category::factory()->withName('Arts')->create();
        Category::factory()->withName('Sports')->create();

        $response = $this->getJson(route('api.categories.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'created_at', 'updated_at']
                ],
                'meta' => ['total_categories']
            ])
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'meta' => ['total_categories' => 3]
            ]);

        $categories = $response->json('data');
        $this->assertEquals('Arts', $categories[0]['name']);
        $this->assertEquals('Sports', $categories[1]['name']);
        $this->assertEquals('Technology', $categories[2]['name']);
    }

    public function test_it_returns_empty_array_when_no_categories_exist()
    {
        $response = $this->getJson(route('api.categories.index'));

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'meta' => ['total_categories' => 0]
            ]);
    }

    public function test_it_returns_correct_total_count_in_meta()
    {
        Category::factory()->count(5)->create();

        $response = $this->getJson(route('api.categories.index'));

        $response->assertStatus(200)
            ->assertJson([
                'meta' => ['total_categories' => 5]
            ]);
    }

    /** @test */
    public function it_uses_category_resource_for_response_structure()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);

        $response = $this->getJson(route('api.categories.index'));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Test Category',
                'slug' => 'test-category'
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }
}
