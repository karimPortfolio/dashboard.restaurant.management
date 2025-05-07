<?php

namespace Tests\Feature\Http\Controllers\Api\V1;


use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ItemControllerTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_can_get_items_list()
    {
        Item::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.items.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'price', 'is_available', 'created_by', 'updated_by']
                ]
            ]);
    }

    public function test_can_create_item()
    {
        $itemData = [
            'name' => 'Test Item',
            'price' => 9.99,
            'is_available' => true
        ];

        $response = $this->postJson(route('api.v1.items.store'), $itemData);

        $response->assertStatus(201)
            ->assertCreated()
            ->assertJsonStructure([
                'data' => ['name', 'price', 'is_available']
            ]);

        $this->assertDatabaseHas('items', [
            'name' => 'Test Item',
            'price' => 9.99,
            'is_available' => true
        ]);
    }

    public function test_can_show_item()
    {
        $item = Item::factory()->create();

        $response = $this->getJson(route('api.v1.items.show', $item));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['name', 'price', 'is_available', 'created_by', 'updated_by', 'menus', 'ingredients']
            ]);
    }

    public function test_can_update_item()
    {
        $item = Item::factory()->create();

        $updateData = [
            'name' => 'Updated Item',
            'price' => 19.99,
            'is_available' => false
        ];

        $response = $this->putJson(route('api.v1.items.update', $item), $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['name', 'price', 'is_available']
            ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'Updated Item',
            'price' => 19.99,
            'is_available' => false
        ]);
    }

    public function test_can_delete_item()
    {
        $item = Item::factory()->create();

        $response = $this->deleteJson(route('api.v1.items.destroy', $item));

        $response->assertNoContent();

        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }


    public function test_show_method_returns_404_for_non_existent_item()
    {
        $response = $this->getJson(route('api.v1.items.show', ['item' => 999]));

        $response->assertStatus(404);
    }

    
    public function test_update_method_returns_404_for_non_existent_item()
    {
        $updateData = [
            'name' => 'Updated Item',
            'price' => 19.99,
            'is_available' => false
        ];

        $response = $this->putJson(route('api.v1.items.update', ['item' => 999]), $updateData);

        $response->assertStatus(404);
    }


    public function test_destroy_method_returns_404_for_non_existent_item()
    {
        $response = $this->deleteJson(route('api.v1.items.destroy', ['item' => 999]));

        $response->assertStatus(404);
    }


    public function test_index_method_returns_empty_array_when_no_items()
    {
        $response = $this->getJson(route('api.v1.items.index'));

        $response->assertStatus(200)
            ->assertJson([
                'data' => []
            ]);
    }


    public function test_store_method_returns_validation_error()
    {
        $response = $this->postJson(route('api.v1.items.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'is_available']);
    }


    public function test_update_method_returns_validation_error()
    {
        $item = Item::factory()->create();

        $response = $this->putJson(route('api.v1.items.update', $item), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'is_available']);
    }
}