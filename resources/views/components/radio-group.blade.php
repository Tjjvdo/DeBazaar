@props(['name', 'label', 'options', 'selected' => null])

<div class="mt-4">
    <x-input-label :value="$label" />

    <div class="flex space-x-4 mt-2">
        @foreach ($options as $value => $text)
            <label class="inline-flex items-center">
                <input type="radio" name="{{ $name }}" value="{{ $value }}" 
                       class="form-radio text-blue-600 focus:ring-blue-500"
                       @checked(old($name, $selected) == $value)>
                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $text }}</span>
            </label>
        @endforeach
    </div>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>