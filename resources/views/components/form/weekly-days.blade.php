@props(['selectedDays' => []])

@php
    $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    $oldDays = old('days', $selectedDays);
@endphp

<div class="mt-4" x-show="frequency === 'weekly'" x-cloak>
    <x-input-label :value="__('Days of Week')" />
    <div class="mt-2 flex flex-wrap gap-4">
        @foreach($days as $day)
            <label class="inline-flex items-center">
                <input type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="days[]" value="{{ $day }}" {{ in_array($day, $oldDays) ? 'checked' : '' }}>
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($day) }}</span>
            </label>
        @endforeach
    </div>
    <x-input-error :messages="$errors->get('days')" class="mt-2" />
</div>
