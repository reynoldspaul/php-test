<?php

use Illuminate\Http\JsonResponse;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Register task-related API routes.
 *
 * Routes:
 * - GET    /tasks          → TaskController@index
 * - POST   /tasks          → TaskController@store
 * - PUT    /tasks/{task}   → TaskController@update (with token verification)
 * - DELETE /tasks/{task}   → TaskController@destroy (with token verification)
 *
 * @return void
 */
Route::prefix('tasks')->group(function (): void {
    /**
     * Get all tasks.
     *
     * @route GET /tasks
     * @return AnonymousResourceCollection
     */
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

    /**
     * Create a new task.
     *
     * @route POST /tasks
     * @return TaskResource
     */
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');

    /**
     * Update an existing task (requires valid token as get param).
     *
     * @route PUT /tasks/{task}
     * @middleware verify.taskToken
     * @return TaskResource
     */
    Route::put('/{task}', [TaskController::class, 'update'])
        ->middleware('verify.taskToken')
        ->name('tasks.update');

    /**
     * Soft delete a task (requires valid token as get param).
     *
     * @route DELETE /tasks/{task}
     * @middleware verify.taskToken
     * @return JsonResponse
     */
    Route::delete('/{task}', [TaskController::class, 'destroy'])
        ->middleware('verify.taskToken')
        ->name('tasks.destroy');
});
