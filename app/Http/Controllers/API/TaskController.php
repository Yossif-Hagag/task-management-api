<?php

namespace App\Http\Controllers\API;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Task::query()->with('assignee', 'dependencies');

            if ($user->isUser()) {
                // users only see assigned tasks
                $query->where('assignee_id', $user->id);
            } else {
                // non-users (managers) can filter by assignee_id if provided
                if ($request->has('assignee_id')) {
                    $query->where('assignee_id', $request->assignee_id);
                }
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('from') || $request->has('to')) {
                $from = $request->has('from') ? \Carbon\Carbon::parse($request->from) : now()->subYear();
                $to = $request->has('to') ? \Carbon\Carbon::parse($request->to) : now()->addYear();

                if ($from->greaterThan($to)) {
                    return $this->apiResponse(null, Response::HTTP_UNPROCESSABLE_ENTITY, 'Invalid date range: from must be <= to');
                }

                $query->whereBetween('due_date', [$from->toDateString(), $to->toDateString()]);
            }

            $tasks = $query->get();

            return $this->apiResponse($tasks, Response::HTTP_OK, 'Tasks fetched successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->apiResponse($e->errors(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Validation failed.');
        } catch (Exception $e) {
            return $this->apiResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $task = Task::with(['assignee', 'dependencies'])->find($id);

            if (! $task) {
                return $this->apiResponse(null, Response::HTTP_NOT_FOUND, 'Task not found');
            }

            if (Auth::user()->isUser() && $task->assignee_id !== Auth::id()) {
                return $this->apiResponse(null, Response::HTTP_FORBIDDEN, 'Unauthorized');
            }

            return $this->apiResponse($task, Response::HTTP_OK, 'Task fetched successfully');

        } catch (Exception $e) {
            return $this->apiResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            if (! Auth::user()->isManager()) {
                return $this->apiResponse(null, Response::HTTP_FORBIDDEN, 'Unauthorized');
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'assignee_id' => 'nullable|exists:users,id',
                'status' => ['required', Rule::in(TaskStatus::values())],
                'due_date' => 'nullable|date',
                'dependencies' => 'nullable|array',
                'dependencies.*' => 'exists:tasks,id',
            ]);

            $task = Task::create($validated);

            if (isset($validated['dependencies'])) {
                $task->dependencies()->sync($validated['dependencies']);
            }

            return $this->apiResponse($task->load('dependencies'), Response::HTTP_CREATED, 'Task Created Successfully');

        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Validation failed.');
        } catch (Exception $e) {
            return $this->apiResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $task = Task::find($id);

            if(! $task) {
                return $this->apiResponse(null, Response::HTTP_NOT_FOUND, 'Task not found');
            }

            if ($user->isUser() && $task->assignee_id !== $user->id) {
                return $this->apiResponse(null, Response::HTTP_FORBIDDEN, 'Unauthorized');
            }

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'assignee_id' => 'sometimes|nullable|exists:users,id',
                'status' => ['sometimes', Rule::in(TaskStatus::values())],
                'due_date' => 'sometimes|nullable|date',
                'dependencies' => 'sometimes|array',
                'dependencies.*' => 'exists:tasks,id',
            ]);

            // Users can only update status
            if ($user->isUser()) {
                $validated = ['status' => $validated['status']];
            }

            // Check dependencies before completing
            if (($validated['status'] ?? $task->status) === TaskStatus::COMPLETED->value) {
                foreach ($task->dependencies as $dep) {
                    if ($dep->status !== TaskStatus::COMPLETED->value) {
                        return $this->apiResponse(
                            null,
                            Response::HTTP_BAD_REQUEST,
                            'Cannot complete task until all dependencies are completed'
                        );
                    }
                }
            }

            $task->update($validated);

            if (isset($validated['dependencies'])) {
                $task->dependencies()->sync($validated['dependencies']);
            }

            $taskUpdated = Task::with(['assignee', 'dependencies'])->find($id);

            return $this->apiResponse($taskUpdated, Response::HTTP_OK, 'Task updated successfully');

        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Validation failed.');
        } catch (Exception $e) {
            return $this->apiResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
