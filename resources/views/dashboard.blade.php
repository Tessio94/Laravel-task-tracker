<x-app-layout title="Dashboard">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $today->format('l, F j, Y') }}
                </p>
            </div>
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('New Task') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Tasks Today -->
                <x-dashboard.stat-card
                    :title="__('Tasks Today')"
                    :value="$stats['completed_today'] . '/' . $stats['tasks_today']"
                    :trend="$stats['today_completion_rate'] . '% completed'"
                    color="blue"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </x-slot>
                </x-dashboard.stat-card>

                <!-- Overdue -->
                <x-dashboard.stat-card
                    :title="__('Overdue')"
                    :value="$stats['overdue']"
                    :trend="$stats['overdue'] > 0 ? __('Needs attention') : __('All caught up!')"
                    :color="$stats['overdue'] > 0 ? 'red' : 'green'"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </x-slot>
                </x-dashboard.stat-card>

                <!-- Total Pending -->
                <x-dashboard.stat-card
                    :title="__('Total Pending')"
                    :value="$stats['total_pending']"
                    :trend="__('Incomplete tasks')"
                    color="amber"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </x-slot>
                </x-dashboard.stat-card>

                <!-- Recently Completed -->
                <x-dashboard.stat-card
                    :title="__('Completed (7 days)')"
                    :value="$recentCompletions"
                    :trend="__('Last 7 days')"
                    color="green"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </x-slot>
                </x-dashboard.stat-card>
            </div>

            <!-- Task Lists -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Overdue Tasks -->
                @if(!empty($overdueTasks))
                    <x-dashboard.task-list
                        :title="__('Overdue Tasks')"
                        :tasks="$overdueTasks"
                        :emptyMessage="__('No overdue tasks')"
                        variant="danger"
                    />
                @endif

                <!-- Upcoming Tasks -->
                <x-dashboard.task-list
                    :title="__('Upcoming Tasks')"
                    :tasks="$upcomingTasks"
                    :emptyMessage="__('No upcoming tasks for today or tomorrow')"
                    class="{{ empty($overdueTasks) ? 'lg:col-span-2' : '' }}"
                />
            </div>

            <!-- Quick Links -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Quick Actions') }}
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            {{ __('View All Tasks') }}
                        </a>
                        <a href="{{ route('tasks.index', ['status' => \App\Enums\TaskStatus::Incomplete->value]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Incomplete Tasks') }}
                        </a>
                        <a href="{{ route('recurring-tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            {{ __('Recurring Tasks') }}
                        </a>
                        <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            {{ __('Categories') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        @vite('resources/js/pages/dashboard.js')
    @endpush
</x-app-layout>
