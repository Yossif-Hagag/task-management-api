<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskDependency;
use Illuminate\Database\Seeder;

class TaskDependencySeeder extends Seeder
{
    public function run(): void
    {

        $tasks = Task::orderBy('created_at', 'desc')->pluck('id')->toArray();

        if (count($tasks) < 2) {
            return;
        }

        $task1 = $tasks[0];
        $task2 = $tasks[1];

        TaskDependency::create([
            'task_id' => $task1,
            'depends_on_task_id' => $task2,
        ]);

        TaskDependency::create([
            'task_id' => $task2,
            'depends_on_task_id' => $task1,
        ]);

    }
}
