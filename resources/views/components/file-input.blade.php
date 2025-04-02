@props(['disabled' => false, 'name' => 'file', 'required' => false, 'accept' => 'application/pdf'])

<input 
    type="file" 
    name="{{ $name }}" 
    @if($required) required @endif 
    accept="{{ $accept }}" 
    @disabled($disabled) 
    {{ $attributes->merge(['class' => 'w-full p-2 border rounded-lg mb-4 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600']) }}>