<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Enums\EmployeeStatus;


class EmployeeStatusControllerTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->actingAs($user);  
    }

    public function test_it_can_get_employee_statuses()
    {
        $response = $this->getJson(route('api.v1.employee-status'));

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'value',
                            'label'
                        ]
                    ]
                ]);

        $expectedData = array_map(function ($status) {
            return [
                'value' => $status->value,
                'label' => $status->label()
            ];
        }, EmployeeStatus::cases());

        $response->assertJson([
            'data' => $expectedData
        ]);
    }
}