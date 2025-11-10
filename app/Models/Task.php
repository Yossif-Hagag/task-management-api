<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assignee_id',
        'status',
        'due_date',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'due_date' => 'date',
    ];
}
