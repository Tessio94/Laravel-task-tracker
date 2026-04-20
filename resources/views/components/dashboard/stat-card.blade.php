@props(['title', 'value', 'icon' => null, 'trend' => null, 'color' => 'gray'])

@php
    $colorClasses = [
        'gray' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
        'blue' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400',
        'green' => 'bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400',
        'red' => 'bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400',
        'amber' => 'bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400',
        'indigo' => 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400',
    ];
    $iconBgClass = $colorClasses[$color] ?? $colorClasses['gray'];
@endphp

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex items-center gap-4">
            @if($icon)
                <div class="shrink-0 p-3 rounded-lg {{ $iconBgClass }}">
                    {{ $icon }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                    {{ $title }}
                </p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $value }}
                </p>
                @if($trend)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $trend }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
