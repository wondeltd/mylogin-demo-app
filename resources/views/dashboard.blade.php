<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     You're logged in as <span class="font-bold">{{ "{$userResponse['data']['first_name']} {$userResponse['data']['last_name']}" }}</span>
                </div>
                <code>
                    <pre>
                    {{  trim(json_encode($userResponse, JSON_PRETTY_PRINT)) }}
                    </pre>
                </code>
            </div>
        </div>
    </div>
</x-app-layout>
