@props(['frequencies', 'selected' => null])

<div class="mt-4">
    <x-input-label for="frequency" :value="__('Frequency')" />
    <x-select-input id="frequency" class="block mt-1 w-full" name="frequency" x-model="frequency" required>
        <option value="">{{ __('Select frequency') }}</option>
        @foreach($frequencies as $freq)
            <option value="{{ $freq->value }}" {{ old('frequency', $selected) == $freq->value ? 'selected' : '' }}>
                {{ ucfirst($freq->value) }}
            </option>
        @endforeach
    </x-select-input>
    <x-input-error :messages="$errors->get('frequency')" class="mt-2" />
</div>
