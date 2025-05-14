<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    /**
     * Display a list of all tasks.
     *
     * @return AnonymousResourceCollection<TaskResource>
     */
    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection(Task::all());
    }

    /**
     * Store a new task.
     *
     * @param  TaskRequest  $request
     * @return TaskResource
     */
    public function store(TaskRequest $request): TaskResource
    {
        $task = Task::create($request->validated());

        return new TaskResource($task);
    }

    /**
     * Updates a task.
     *
     * @param  TaskRequest  $request
     * @param  Task  $task
     * @return TaskResource
     */
    public function update(TaskRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Soft deletes a task.
     *
     * @param  Task  $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }
}
