<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Recurring Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('recurring-tasks.update', ['recurring_task' => $recurringTask['id']]) }}" x-data="{ frequency: '{{ old('frequency', $recurringTask['frequency']) }}', submitting: false }" @submit="submitting = true">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $recurringTask['title'])" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-textarea-input id="description" class="block mt-1 w-full" name="description" rows="4">{{ old('description', $recurringTask['description']) }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <x-form.category-select :categories="$categories" :selected="$recurringTask['category']['id'] ?? null" />
                        <x-form.frequency-select :frequencies="$frequencies" :selected="$recurringTask['frequency']" />
                        <x-form.weekly-days :selectedDays="$recurringTask['frequency_config']['days'] ?? []" />
                        <x-form.monthly-day :dayOfMonth="$recurringTask['frequency_config']['day_of_month'] ?? null" />

                        <!-- Start Date -->
                        <div class="mt-4">
                            <x-input-label for="start_date" :value="__('Start Date (Optional)')" />
                            {{-- <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', $recurringTask['start_date']['datetime'] ?? null)" /> --}}
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', $recurringTask['start_date'] ?? null)" />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <!-- End Date -->
                        <div class="mt-4">
                            <x-input-label for="end_date" :value="__('End Date (Optional)')" />
                            {{-- <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $recurringTask['end_date']['datetime'] ?? null)" /> --}}
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $recurringTask['end_date'] ?? null)" />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('recurring-tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button ::disabled="submitting">
                                <span x-show="!submitting">{{ __('Update Recurring Task') }}</span>
                                <span x-show="submitting" x-cloak>{{ __('Saving...') }}</span>
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
