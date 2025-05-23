<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the task resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'created_at'  => $this->created_at->toDateTimeString(),
            'updated_at'  => $this->updated_at->toDateTimeString(),
            'edit_url'    => $this->when($request->routeIs('tasks.store'), url("/api/tasks/{$this->id}?token={$this->secure_token}")),
            'delete_url'  => $this->when($request->routeIs('tasks.store'), url("/api/tasks/{$this->id}?token={$this->secure_token}")),
        ];
    }
}
