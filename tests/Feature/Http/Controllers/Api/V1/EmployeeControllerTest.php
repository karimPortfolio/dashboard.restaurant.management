<?php
namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Enums\EmployeePosition;
use App\Enums\EmployeeStatus;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        $response = $this->getJson(route('api.v1.employees.index'));

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
        Storage::fake('s3');

        $employeeImage = UploadedFile::fake()->image('employee.jpg');

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
            'photo' => $employeeImage,
        ];

        $response = $this->postJson(route('api.v1.employees.store'), $employeeData);

        $employee = Employee::latest()->first();

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

        $this->assertDatabaseHas('employees', [
            'first_name' => $employeeData['first_name'],
            'last_name' => $employeeData['last_name'],
            'email' => $employeeData['email'],
            'phone' => $employeeData['phone'],
            'salary' => $employeeData['salary'],
            'status' => EmployeeStatus::ACTIVE->toArray(),
            'cin_number' => $employeeData['cin_number'],
            'cnss_number' => $employeeData['cnss_number'],
            'joining_date' => $employeeData['joining_date'],
            'position' => EmployeePosition::MANAGER->toArray(),
        ]);

        $media = $employee->getFirstMedia('employees-photo');
        $this->assertNotNull($media);

        Storage::disk('s3')->assertExists(
            $media->getPathRelativeToRoot()
        );

    }


    public function test_can_show_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson(route('api.v1.employees.show', $employee));

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

        $response = $this->putJson(route('api.v1.employees.update', $employee), $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'Updated FirstName',
                    'last_name' => 'Updated LastName'
                ]
            ]);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'first_name' => 'Updated FirstName',
            'last_name' => 'Updated LastName',
            'email' => $updateData['email'],
            'phone' => $updateData['phone'],
            'salary' => $updateData['salary'],
            'status' => EmployeeStatus::INACTIVE->toArray(),
            'cin_number' => $updateData['cin_number'],
            'cnss_number' => $updateData['cnss_number'],
            'joining_date' => $updateData['joining_date'],
            'position' => EmployeePosition::DISHWASHER->toArray(),
        ]);
    }

    public function test_can_update_employee_photo()
    {
        Storage::fake('s3');

        $employee = Employee::factory()->create();

        $initialPhoto = UploadedFile::fake()->image('initial.jpg');

        $employee->addMedia($initialPhoto)
            ->toMediaCollection('employees-photo', 's3');

        $oldMediaPath = $employee->getFirstMedia('employees-photo')->getPathRelativeToRoot();

        $newPhoto = UploadedFile::fake()->image('new_employee.jpg');

        $employeeData = [
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'email' => $employee->email,
            'phone' => (string) $employee->phone,
            'salary' => $employee->salary,
            'status' => EmployeeStatus::ACTIVE->toArray(),
            'cin_number' => $employee->cin_number,
            'cnss_number' => (string) $employee->cnss_number,
            'joining_date' => now()->format('Y-m-d'),
            'position' => EmployeePosition::MANAGER->toArray(),
            'photo' => $newPhoto,
        ];

        $response = $this->putJson(route('api.v1.employees.update', $employee), $employeeData);

        $response->assertStatus(200);

        $employee->refresh();
        $media = $employee->getFirstMedia('employees-photo');

        $this->assertNotNull($media);
        Storage::disk('s3')->assertExists($media->getPathRelativeToRoot());

        Storage::disk('s3')->assertMissing($oldMediaPath);

        $this->assertEquals('employees-photo', $media->collection_name);
        $this->assertEquals($employee->id, $media->model_id);
        $this->assertEquals('App\Models\Employee', $media->model_type);
        $this->assertEquals('image/jpeg', $media->mime_type);
        $this->assertEquals($newPhoto->extension(), $media->extension);
        $this->assertEquals($newPhoto->getClientMimeType(), $media->mime_type);
    }

    public function test_can_delete_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson(route('api.v1.employees.destroy', $employee));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_validates_required_fields_for_store()
    {
        $response = $this->postJson(route('api.v1.employees.store'), []);

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

        $response = $this->putJson(route('api.v1.employees.update', $employee), []);

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
        $response = $this->getJson(route('api.v1.employees.show', 999));

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Employee] 999'
            ]);
    }

    public function test_update_method_returns_404_if_employee_not_found()
    {
        $response = $this->putJson(route('api.v1.employees.update', 999), [
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
        $response = $this->deleteJson(route('api.v1.employees.destroy', 999));

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Employee] 999'
            ]);
    }
}