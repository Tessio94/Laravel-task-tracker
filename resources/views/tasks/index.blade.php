<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tasks') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('recurring-tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Recurring Tasks') }}
                </a>
                @if($tasks)
                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('New Task') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('tasks.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <x-select-input id="status" class="block mt-1 w-full" name="status">
                                <option value="">{{ __('All') }}</option>
                                <option value="incomplete" {{ ($filters['status'] ?? '') === \App\Enums\TaskStatus::Incomplete->value ? 'selected' : '' }}>{{ __('Incomplete') }}</option>
                                <option value="completed" {{ ($filters['status'] ?? '') === \App\Enums\TaskStatus::Completed->value ? 'selected' : '' }}>{{ __('Completed') }}</option>
                            </x-select-input>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <x-select-input id="category_id" class="block mt-1 w-full" name="category_id">
                                <option value="">{{ __('All Categories') }}</option>
                                @foreach($categories as $categoryId => $category)
                                    <option value="{{ $categoryId }}" {{ ($filters['category_id'] ?? '') == $categoryId ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Date From Filter -->
                        <div>
                            <x-input-label for="date_from" :value="__('From Date')" />
                            <x-text-input id="date_from" class="block mt-1 w-full" type="date" name="date_from" :value="$filters['date_from'] ?? ''" />
                        </div>

                        <!-- Date To Filter -->
                        <div>
                            <x-input-label for="date_to" :value="__('To Date')" />
                            <x-text-input id="date_to" class="block mt-1 w-full" type="date" name="date_to" :value="$filters['date_to'] ?? ''" />
                        </div>

                        <!-- Filter Buttons -->
                        <div class="md:col-span-4 flex gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Filter') }}
                            </button>
                            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Clear') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(count($tasks) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('Status') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('Title') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('Category') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('Task Date') }}
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">{{ __('Actions') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($tasks as $task)
                                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/5" data-task-item data-completed="{{ $task['completed_at'] ? 'true' : 'false' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button data-task-toggle data-task-id="{{ $task['id'] }}" type="button" class="cursor-pointer focus:outline-hidden">
                                                    <svg class="group-data-[completed=true]:block hidden w-6 h-6 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <svg class="group-data-[completed=false]:block hidden w-6 h-6 text-gray-400 dark:text-gray-500 group-hover:text-indigo-500 dark:group-hover:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 group-data-[completed=true]:line-through">
                                                    {{ $task['title'] }}
                                                </div>
                                                @if($task['description'])
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ Str::limit($task['description'], 50) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $task['category']['name'] ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $task['task_date']['display'] ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('tasks.edit', ['task' => $task['id']]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">
                                                    {{ __('Edit') }}
                                                </a>
                                                <button type="button" data-task-delete data-id="{{ $task['id'] }}" class="cursor-pointer text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 disabled:opacity-50">
                                                    {{ __('Delete') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('No tasks found.') }}</p>
                            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Create Your First Task') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        @vite('resources/js/pages/tasks-index.js')
    @endpush
</x-app-layout>
