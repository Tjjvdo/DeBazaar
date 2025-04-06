<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('landingpage.my_landingpage') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('landingpage.save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                
                    <div class="mb-4">
                        <label class="block font-bold mb-2 text-gray-700 dark:text-gray-300">{{ __('landingpage.custom_url') }}</label>
                        <input type="text" name="custom_url" value="{{ old('custom_url', $landingPage->slug ?? '') }}" class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2 text-gray-700 dark:text-gray-300">{{ __('landingpage.color_picker') }}</label>
                        <input type="color" name="color" value="{{ old('color', $landingPage->color ?? '#ff0000') }}" class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700" >
                    </div>
                
                    <div class="mb-4">
                        <label class="block font-bold mb-2 text-gray-700 dark:text-gray-300">{{ __('landingpage.choose_components') }}</label>
                        <div class="flex space-x-4">
                            <div>
                                <input type="checkbox" id="use_information" name="use_information" 
                                    {{ old('use_information', isset($landingPage) && in_array('information', $landingPage->component_order)) ? 'checked' : '' }}>
                                <label for="use_information" class="text-gray-700 dark:text-gray-300">{{ __('landingpage.information_block_check') }}</label>
                            </div>
                            <div>
                                <input type="checkbox" id="use_image" name="use_image" 
                                    {{ old('use_image', isset($landingPage) && in_array('image', $landingPage->component_order)) ? 'checked' : '' }}>
                                <label for="use_image" class="text-gray-700 dark:text-gray-300">{{ __('landingpage.image_block_check') }}</label>
                            </div>
                            <div>
                                <input type="checkbox" id="use_advertisements" name="use_advertisements" 
                                    {{ old('use_advertisements', isset($landingPage) && in_array('advertisements', $landingPage->component_order)) ? 'checked' : '' }}>
                                <label for="use_advertisements" class="text-gray-700 dark:text-gray-300">{{ __('landingpage.advertisements_block_check') }}</label>
                            </div>
                        </div>
                    </div>
                
                    <div class="mb-4">
                        <label class="block font-bold mb-2 text-gray-700 dark:text-gray-300">{{ __('landingpage.component_order') }}</label>
                        <div class="flex space-x-4">
                            <div>
                                <label for="component_1" class="text-gray-700 dark:text-gray-300">{{ __('landingpage.first_component') }}</label>
                                <select id="component_1" name="component_order[]" class="w-full p-2 border rounded" required>
                                </select>
                            </div>
                            <div>
                                <label for="component_2" class="text-gray-700 dark:text-gray-300">{{ __('landingpage.second_component') }}</label>
                                <select id="component_2" name="component_order[]" class="w-full p-2 border rounded" required>
                                </select>
                            </div>
                            <div>
                                <label for="component_3" class="text-gray-700 dark:text-gray-300">{{ __('landingpage.third_component') }}</label>
                                <select id="component_3" name="component_order[]" class="w-full p-2 border rounded" required>
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <div class="mb-4">
                        <label for="information_text" class="block font-bold mb-2 text-gray-700 dark:text-gray-300">{{ __('landingpage.information_block_content') }}</label>
                        <textarea name="information_text" id="information_text" class="w-full p-2 border rounded">{{ old('information_text', $landingPage->info_content ?? '') }}</textarea>
                    </div>
                
                    <div class="mb-4">
                        <label class="block font-bold mb-2 text-gray-700 dark:text-gray-300">{{ __('landingpage.upload_image') }}</label>
                        <input type="file" name="image" class="'w-full p-2 border rounded-lg mb-4 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600'">
                        @if (!empty($landingPage->image_path))
                            <p class="mt-2 text-gray-700 dark:text-gray-300">{{ __('landingpage.current_image') }}</p>
                            <img src="{{ asset('storage/' . $landingPage->image_path) }}" alt="Uploaded Image" class="w-48 mt-2 rounded shadow">
                        @endif
                    </div>
                
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('landingpage.save_button') }}</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const component1 = document.getElementById("component_1");
            const component2 = document.getElementById("component_2");
            const component3 = document.getElementById("component_3");

            const useInformation = document.getElementById("use_information");
            const useImage = document.getElementById("use_image");
            const useAdvertisements = document.getElementById("use_advertisements");

            function updateComponent2() {
                const selectedComponents = [];
                if (useInformation.checked) selectedComponents.push('information');
                if (useImage.checked) selectedComponents.push('image');
                if (useAdvertisements.checked) selectedComponents.push('advertisements');

                const selectedValue1 = component1.value;
                const remainingForComponent2 = selectedComponents.filter(item => item !== selectedValue1);

                component2.innerHTML = '';

                if(selectedComponents.length >= 2){
                    remainingForComponent2.forEach(function(component) {
                        const option = document.createElement('option');
                        option.value = component;
                        option.textContent = component.charAt(0).toUpperCase() + component.slice(1);
                        component2.appendChild(option);
                    });

                    updateComponent3();
                }
            }

            function updateComponent3() {
                const selectedComponents = [];
                if (useInformation.checked) selectedComponents.push('information');
                if (useImage.checked) selectedComponents.push('image');
                if (useAdvertisements.checked) selectedComponents.push('advertisements');

                const selectedValue1 = component1.value;
                const selectedValue2 = component2.value;
                const remainingForComponent3 = selectedComponents.filter(item => item !== selectedValue1 && item !== selectedValue2);

                component3.innerHTML = '';

                if (remainingForComponent3.length > 0 && selectedComponents.length === 3) {
                    const option = document.createElement('option');
                    option.value = remainingForComponent3[0];
                    option.textContent = remainingForComponent3[0].charAt(0).toUpperCase() + remainingForComponent3[0].slice(1);
                    component3.appendChild(option);
                }
            }

            function updateComponentOptions() {
                const selectedComponents = [];
                if (useInformation.checked) selectedComponents.push('information');
                if (useImage.checked) selectedComponents.push('image');
                if (useAdvertisements.checked) selectedComponents.push('advertisements');

                component1.innerHTML = '';
                component2.innerHTML = '';
                component3.innerHTML = '';

                component1.setAttribute('disabled', 'true');
                component2.setAttribute('disabled', 'true');
                component3.setAttribute('disabled', 'true');

                if (selectedComponents.length === 0) return;

                component1.removeAttribute('disabled');
                if (selectedComponents.length >= 2) component2.removeAttribute('disabled');
                if (selectedComponents.length === 3) component3.removeAttribute('disabled');

                selectedComponents.forEach(function(component) {
                    const option = document.createElement('option');
                    option.value = component;
                    option.textContent = component.charAt(0).toUpperCase() + component.slice(1);
                    component1.appendChild(option);
                });

                updateComponent2();
            }

            useInformation.addEventListener('change', updateComponentOptions);
            useImage.addEventListener('change', updateComponentOptions);
            useAdvertisements.addEventListener('change', updateComponentOptions);

            component1.addEventListener('change', function() {
                updateComponent2();
            });

            component2.addEventListener('change', function() {
                updateComponent3();
            });

            updateComponentOptions();
        });
    </script>
    
</x-app-layout>