<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Default Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <!-- GDPR Section -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">GDPR Settings</h2>

                <!-- Success Message -->
                @if(session('success'))
                    <p class="text-green-600 mb-2">{{ session('success') }}</p>
                @endif

                <!-- Error Message -->
                @if($errors->any())
                    <p class="text-red-600 mb-2">{{ $errors->first() }}</p>
                @endif

                <!-- GDPR Download Form -->
                <form method="POST" action="/gdpr/download" class="space-y-4">
                    @csrf

                    <input type="password" 
                           name="password" 
                           placeholder="Enter your password"
                           class="w-full border px-4 py-2 rounded"
                           required>

                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Download My Data
                    </button>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>