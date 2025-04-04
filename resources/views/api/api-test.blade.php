<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Raw API Response - Active Advertisements</h2>
                <pre id="json-container" class="mt-4 bg-gray-100 p-4 rounded-md"></pre>
            </div>
        </div>
    </div>

    <script>
        fetch('http://debazaar.test/api/active-ads/test', {
            method: 'GET',
            headers: {
                'X-API-KEY': 'tfUVGtd1OcWTV6HcqwUGHp3ziIBnf3Sl'
            }
        })
        .then(response => response.json())
        .then(data => {
            const jsonContainer = document.getElementById('json-container');
            jsonContainer.textContent = JSON.stringify(data, null, 2); // Pretty print the JSON
        })
        .catch(error => {
            console.error('Error:', error);
            const jsonContainer = document.getElementById('json-container');
            jsonContainer.textContent = 'Error fetching data.';
        });
    </script>
</x-app-layout>
