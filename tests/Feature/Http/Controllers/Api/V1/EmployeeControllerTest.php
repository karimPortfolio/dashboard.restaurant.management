<?php
namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Enums\EmployeePosition;
use App\Enums\EmployeeStatus;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;


class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_can_get_all_employees()
    {
        $employees = Employee::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/employees');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'full_name',
                    'created_at'
                ]
            ]
        ]);
    }

    public function test_can_create_employee()
    {
        $employeeData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'salary' => $this->faker->numberBetween(2000, 10000),
            'status' => EmployeeStatus::ACTIVE->toArray(),
            'cin_number' => $this->faker->unique()->randomAscii(),
            'cnss_number' => $this->faker->unique()->randomAscii(),
            'joining_date' => $this->faker->date(),
            'position' => EmployeePosition::MANAGER->toArray(),
            'photo' => UploadedFile::fake()->image('employee.jpg')
        ];

        $response = $this->postJson('/api/v1/employees', $employeeData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'created_at'
                ]
            ]);
    }


    public function test_can_show_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson("/api/v1/employees/{$employee->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $employee->id,
                    'first_name' => $employee->first_name,
                    'last_name' => $employee->last_name
                ]
            ]);
    }


    public function test_can_update_employee()
    {
        $employee = Employee::factory()->create();
        
        $updateData = [
            'first_name' => 'Updated FirstName',
            'last_name' => 'Updated LastName',
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'salary' => 5000,
            'status' => EmployeeStatus::INACTIVE->toArray(),
            'cin_number' => $this->faker->unique()->randomAscii(),
            'cnss_number' => $this->faker->unique()->randomAscii(),
            'joining_date' => $this->faker->date(),
            'position' => EmployeePosition::DISHWASHER->toArray(),
        ];

        $response = $this->putJson("/api/v1/employees/{$employee->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'Updated FirstName',
                    'last_name' => 'Updated LastName'
                ]
            ]);
    }

    public function test_can_delete_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson("/api/v1/employees/{$employee->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_validates_required_fields_for_store()
    {
        $response = $this->postJson('/api/v1/employees', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'email',
                'phone',
                'salary',
                'status',
                'cin_number',
                'cnss_number',
                'joining_date',
                'position'
            ]);
    }


    public function test_validates_required_fields_for_update()
    {
        $employee = Employee::factory()->create();

        $response = $this->putJson("/api/v1/employees/{$employee->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'email',
                'phone',
                'salary',
                'status',
                'cin_number',
                'cnss_number',
                'joining_date',
                'position'
            ]);
    }


    public function test_show_method_returns_404_if_employee_not_found()
    {
        $response = $this->getJson('/api/v1/employees/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Employee] 999'
            ]);
    }

    public function test_update_method_returns_404_if_employee_not_found()
    {
        $response = $this->putJson('/api/v1/employees/999', [
            'first_name' => 'Updated FirstName',
            'last_name' => 'Updated LastName',
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'salary' => 5000,
            'status' => EmployeeStatus::INACTIVE->toArray(),
            'cin_number' => $this->faker->unique()->randomAscii(),
            'cnss_number' => $this->faker->unique()->randomAscii(),
            'joining_date' => $this->faker->date(),
            'position' => EmployeePosition::DISHWASHER->toArray(),
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Employee] 999'
            ]);
    }


    public function test_delete_method_returns_404_if_employee_not_found()
    {
        $response = $this->deleteJson('/api/v1/employees/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Employee] 999'
            ]);
    }
}