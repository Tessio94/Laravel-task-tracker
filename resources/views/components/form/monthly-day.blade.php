@props(['dayOfMonth' => null])

<div class="mt-4" x-show="frequency === 'monthly'" x-cloak>
    <x-input-label for="day_of_month" :value="__('Day of Month')" />
    <x-text-input id="day_of_month" class="block mt-1 w-32" type="number" name="day_of_month" :value="old('day_of_month', $dayOfMonth)" min="1" max="31" />
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Enter a day between 1 and 31') }}</p>
    <x-input-error :messages="$errors->get('day_of_month')" class="mt-2" />
</div>
