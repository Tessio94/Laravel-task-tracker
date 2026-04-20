<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();


        $query = $user->tasks()->with('category')
            ->when($request->status === 'completed', fn($query) => $query->whereNotNull('completed_at'))
            ->when($request->status === 'incomplete', fn($query) => $query->whereNull('completed_at'))
            ->when($request->filled('category_id'), fn($query) => $query->where('category_id', $request->category_id))
            ->when($request->filled('date_from'), fn($query) => $query->whereDate('task_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($query) => $query->whereDate('task_date', '<=', $request->date_to))
            ->latest();

        $tasks = $query->paginate();

        $categories = $user->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();;

        return view('tasks.index', [
            'tasks' => $tasks->toResourceCollection()->resolve(),
            'links' => fn() => $tasks->links(),
            'categories' => $categories,
            'filters' => $request->only(['status', 'category_id', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = $request->user()->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();

        return view('tasks.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $taskData = $request->validated();

        $taskData['category_id'] = Category::where('uuid', $request->category_id)->value('id');

        $request->user()->tasks()->create($taskData);

        return to_route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Task $task)
    {
        $task->load('category');

        $categories = $request->user()->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();

        return view('tasks.edit', ['task' => $task->toResource()->resolve(), 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validData = $request->validated();


        if($request->category_id) {

            $category = Category::where('uuid', $request->category_id)->first();

            if(! $category || $request->user()->cannot('manage', $category)) {
                throw ValidationException::withMessages(['category_id' => 'The given category id does not exist.']);
            }

            $task->category()->associate($category);

            unset($validData['category_id']);

            $validData['is_recurring'] = $request->boolean('is_recurring');
        }

        $task->fill($validData);

        $task->save();

        return to_route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return to_route('tasks.index')->with('success', 'Task deleted successfully.');
    }


    /**
     * Toggle the completion status of the task
     */
    public function toggleCompletion(Task $task)
    {

        $task->completed_at = $task->completed_at ? null : now();

        $task->save();

        return response()->json([
            'completed' => (bool) $task->completed_at
        ]);
    }
}
