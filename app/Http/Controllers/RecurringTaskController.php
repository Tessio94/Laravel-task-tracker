<?php

namespace App\Http\Controllers;

use App\Enums\TaskFrequency;
use App\Http\Requests\StoreRecurringTaskRequest;
use App\Http\Requests\UpdateRecurringTaskRequest;
use App\Models\Category;
use App\Models\RecurringTask;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RecurringTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $recurringTasks = $request->user()
            ->recurringTasks()
            ->with('category')
            ->latest()
            ->paginate();

        $categories = $request->user()->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();

        dd($request->user()->recurringTasks()->latest());

        return view('recurring-tasks.index', [
            'recurringTasks' => $recurringTasks->toResourceCollection()->resolve(),
            'links' => fn () => $recurringTasks->links(),
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = $request->user()->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();

        return view('recurring-tasks.create', [
            'categories' => $categories,
            'frequencies' => TaskFrequency::cases()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecurringTaskRequest $request)
    {
        $data = $request->validated();

        if ($request->category_id) {
            $category = Category::where('uuid', $request->category_id)->first();

            if (! $category || $request->user()->cannot('manage', $category)) {
                throw ValidationException::withMessages(['category_id' => 'The given category id does not exist.']);
            }

            $data['category_id'] = $category->id;
        }

        $data['frequency_config'] = $this->buildFrequencyConfig($data);

        unset($data['days'], $data['day_of_month']); // don't need them no more as they will be part of 'frequency_config'

        $request->user()->recurringTasks()->create($data);

        return to_route('recurring-tasks.index')->with('success', 'Recurring task created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, RecurringTask $recurringTask)
    {
        $recurringTask->load('category');

        $categories = $request->user()->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();

        return view('recurring-tasks.edit', [
            'recurringTask' => $recurringTask->toResource()->resolve(),
            'categories' => $categories,
            'frequencies' => TaskFrequency::cases()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecurringTaskRequest $request, RecurringTask $recurringTask)
    {
        $data = $request->validated();

        if ($request->category_id) {
            $category = Category::where('uuid', $request->category_id)->first();

            if (! $category || $request->user()->cannot('manage', $category)) {
                throw ValidationException::withMessages(['category_id' => 'The given category id does not exist.']);
            }

            $recurringTask->category()->associate($category);

            unset($data['category_id']);
        }

        $data['frequency_config'] = $this->buildFrequencyConfig($data);

        unset($data['days'], $data['day_of_month']);

        $recurringTask->fill($data);
        $recurringTask->save();

        return to_route('recurring-tasks.index')->with('success', 'Recurring task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecurringTask $recurringTask)
    {
        $recurringTask->delete();

        return to_route('recurring-tasks.index')->with('success', 'Recurring task deleted successfully.');
    }

    private function buildFrequencyConfig(array $data): ?array
    {
        $frequency = $data['frequency']; // will be set on $data as it is part of FormRequest

        if ($frequency === TaskFrequency::Weekly->value && isset($data['days'])) {
            return ['days' => $data['days']];
        }

        if ($frequency === TaskFrequency::Monthly->value && isset($data['day_of_month'])) {
            return ['day_of_month' => (int) $data['day_of_month']];
        }

        return null;
    }
}
