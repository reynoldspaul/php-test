<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_can_list_all_tasks(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_api_can_create_a_task_with_valid_data(): void
    {
        $data = [
            'name' => 'Test Task',
            'description' => 'This is a valid description of at least 10 characters.',
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'created_at',
                    'updated_at',
                    'edit_url',
                    'delete_url',
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'name' => $data['name'],
        ]);
    }

    public function test_api_fails_to_create_task_with_invalid_data(): void
    {
        $data = [
            'name' => 'sh', // too short
            'description' => 'Short', // too short
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'description']);
    }

    public function test_api_can_update_a_task_with_valid_secure_token(): void
    {
        $task = Task::factory()->create([
            'secure_token' => Str::uuid(),
        ]);

        $data = [
            'name' => 'Updated Name',
            'description' => 'Updated description with valid length.',
        ];

        $response = $this->putJson("/api/tasks/{$task->id}?token={$task->secure_token}", $data);

        $response->assertOk();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => $data['name'],
        ]);
    }

    public function test_api_rejects_a_task_update_with_invalid_secure_token(): void
    {
        $task = Task::factory()->create([
            'secure_token' => Str::uuid(),
        ]);

        $data = [
            'name' => 'Invalid Update',
            'description' => 'Still a valid length description.',
        ];

        $response = $this->putJson("/api/tasks/{$task->id}?token=invalid", $data);

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['name' => $task->name]);
    }

    public function test_api_can_soft_delete_task_with_valid_token(): void
    {
        $task = Task::factory()->create([
            'secure_token' => Str::uuid(),
        ]);

        $this->deleteJson("/api/tasks/{$task->id}?token={$task->secure_token}");

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_api_rejects_task_deletion_with_invalid_token(): void
    {
        $task = Task::factory()->create([
            'secure_token' => Str::uuid(),
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}?token=invalid");

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function test_requests_are_logged_to_log_file(): void
    {
        $uniqueMarker = 'REQUEST_LOG_TEST_' . Str::random(10);

        $this->getJson("/api/tasks?log-test={$uniqueMarker}")->assertOk();

        $logContents = File::get(storage_path('logs/laravel.log'));

        $this->assertStringContainsString($uniqueMarker, $logContents);
    }
}
