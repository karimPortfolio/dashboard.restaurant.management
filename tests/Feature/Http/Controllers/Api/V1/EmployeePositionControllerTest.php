<?php
namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Enums\EmployeePosition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class EmployeePositionControllerTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_it_returns_list_of_employee_positions()
    {
        $response = $this->getJson( route('api.v1.employee-positions'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'value',
                        'label'
                    ]
                ]
            ]);

        $expectedPositions = array_map(function ($position) {
            return [
                'value' => $position->value,
                'label' => $position->label()
            ];
        }, EmployeePosition::cases());

        $response->assertJson([
            'data' => $expectedPositions
        ]);
    }
}