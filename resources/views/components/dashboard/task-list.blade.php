@props(['title', 'tasks', 'emptyMessage' => 'No tasks found.', 'showDate' => true, 'variant' => 'default'])

@php
    $variantClasses = [
        'default' => '',
        'danger' => 'border-l-4 border-red-500 dark:border-red-400',
    ];
    $borderClass = $variantClasses[$variant] ?? '';
@endphp

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg {{ $borderClass }}">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            {{ $title }}
        </h3>

        @if(empty($tasks))
            <p class="text-gray-500 dark:text-gray-400 text-sm py-4 text-center">
                {{ $emptyMessage }}
            </p>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($tasks as $task)
                    <li class="py-3 first:pt-0 last:pb-0 group" data-task-item data-completed="{{ $task['completed_at'] ? 'true' : 'false' }}">
                        <div class="flex gap-3">
                            <button type="button" class="cursor-pointer focus:outline-none" data-task-toggle data-task-id="{{ $task['id'] }}">
                                <svg class="group-data-[completed=false]:block hidden w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                                </svg>
                                <svg class="group-data-[completed=true]:block hidden w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('tasks.edit', ['task' => $task['id']]) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors group-data-[completed=true]:line-through">
                                    {{ $task['title'] }}
                                </a>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($task['category'] ?? null)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                            {{ $task['category']['name'] }}
                                        </span>
                                    @endif
                                    @if($showDate && ($task['task_date'] ?? null))
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{-- {{ $task['task_date']['display'] }} --}}
                                            {{ $task['task_date'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
