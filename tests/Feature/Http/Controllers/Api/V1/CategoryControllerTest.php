<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_fetch_categories(): void
    {
        $categories = Category::factory(5)->create();

        $response = $this->getJson(route('api.v1.categories.index'));

        $response->assertStatus(200);

        $response->assertJsonCount(5, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'parent_category',
                    'image',
                    'created_by',
                    'created_at',
                ]
            ]
        ]);
    }

    public function test_it_can_fetch_categories_with_pagination(): void
    {
        $categories = Category::factory(20)->create();

        $response = $this->getJson(route('api.v1.categories.index', ['per_page' => 10]));

        $response->assertStatus(200);

        $response->assertJsonCount(10, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'parent_category',
                    'image',
                    'created_by',
                    'created_at',
                ]
            ]
        ]);
    }

    public function test_it_returns_empty_collection_when_no_categories_found(): void
    {
        $response = $this->getJson(route('api.v1.categories.index'));

        $response->assertStatus(200);

        $response->assertJsonCount(0, 'data');

        $response->assertJson([
            'data' => [],
        ]);
    }

    public function test_it_can_create_category(): void
    {
        $categoryData = [
            'name' => 'Test Category',
            'parent_category' => Category::factory()->create()->id, 
        ];

        $response = $this->postJson(route('api.v1.categories.store'), $categoryData);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'created_at',
            ]
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
        ]);
        
    }

    public function test_it_returns_422_if_validation_failed(): void
    {
        $response = $this->postJson(route('api.v1.categories.store'), [
            'parent_category' => Category::factory()->create()->id,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name']);
    }

    public function test_it_can_get_single_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson(route('api.v1.categories.show', $category));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'parent_category',
                'created_by',
                'created_at',
            ]
        ]);
    }

    public function test_show_method_returns_404_if_category_not_exists(): void
    {
        $response = $this->getJson(route('api.v1.categories.show', 999));

        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'No query results for model [App\\Models\\Category] 999',
        ]);
    }

    public function test_it_can_update_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->putJson(route('api.v1.categories.update', $category), [
            'name' => 'Updated Category',
            'parent_category' => null,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'created_at',
            ]
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Updated Category',
        ]);
    }

    public function test_update_method_returns_404_if_category_not_exists(): void
    {
        $response = $this->putJson(route('api.v1.categories.update', 999), [
            'name' => 'Updated Category',
            'parent_category' => null,
        ]);

        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'No query results for model [App\\Models\\Category] 999',
        ]);
    }

    
    public function test_it_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson(route('api.v1.categories.destroy', $category));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    

}
