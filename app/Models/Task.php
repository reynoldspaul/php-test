<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description', 'secure_token'];

    /**
     * Booted method - create unique secure_token for all new tasks
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($task) {
            $task->secure_token = Str::uuid();
        });
    }
}
