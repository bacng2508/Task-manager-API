<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\TaskCollection;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function index(Request $request) {
        // return response()->json(Task::all());

        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters('id')
            ->defaultSort('created_at')
            ->allowedSorts(['title', 'is_done', 'created_at'])
            ->paginate();

        return new TaskCollection($tasks);

    }

    public function show(Request $request, Task $task) {
        return new TaskResource($task);
    }

    public function store(StoreTaskRequest $request) {
        $validated = $request->validated();

        // $task = ask::create($validated);
        $task = Auth::user()->tasks()->create($validated);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task) {
        $validated = $request->validated();

        $task->update($validated);

        return new TaskResource($task);
    }

    public function destroy(Request $request, Task $task) {
        $task->delete();

        return response()->noContent();
    }
}
