@props(['categories', 'selected' => null])

<div class="mt-4">
    <x-input-label for="category_id" :value="__('Category')" />
    <x-select-input id="category_id" class="block mt-1 w-full" name="category_id">
        <option value="">{{ __('Select a category') }}</option>
        @foreach($categories as $categoryId => $category)
            <option value="{{ $categoryId }}" {{ old('category_id', $selected) == $categoryId ? 'selected' : '' }}>
                {{ $category }}
            </option>
        @endforeach
    </x-select-input>
    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
</div>
