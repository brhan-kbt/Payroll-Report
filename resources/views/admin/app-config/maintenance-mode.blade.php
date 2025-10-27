<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Maintenance Mode') }}
            </h2>
            <a href="{{ route('admin.app-config.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Configurations
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($maintenanceConfig && $maintenanceConfig->typed_value)
                                    <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                @else
                                    <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-gray-900">
                                    @if($maintenanceConfig && $maintenanceConfig->typed_value)
                                        Maintenance Mode is <span class="text-red-600 font-bold">ENABLED</span>
                                    @else
                                        Maintenance Mode is <span class="text-green-600 font-bold">DISABLED</span>
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-500">
                                    @if($maintenanceConfig && $maintenanceConfig->typed_value)
                                        Your application is currently in maintenance mode. Users will see the maintenance message when accessing the API.
                                    @else
                                        Your application is running normally. Users can access all features.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.app-config.update-maintenance-mode') }}">
                        @csrf

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="maintenance_mode"
                                       value="1"
                                       {{ old('maintenance_mode', $maintenanceConfig && $maintenanceConfig->typed_value) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-lg focus:ring-indigo-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Enable Maintenance Mode</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">
                                When enabled, all API endpoints (except admin routes) will return a maintenance message.
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="maintenance_message" class="block text-sm font-medium text-gray-700">Maintenance Message</label>
                            <textarea id="maintenance_message"
                                      name="maintenance_message"
                                      rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      required>{{ old('maintenance_message', $maintenanceMessage ? $maintenanceMessage->value : 'We are currently performing maintenance. Please try again later.') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                This message will be displayed to users when maintenance mode is enabled.
                            </p>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Maintenance Settings
                            </button>
                        </div>
                    </form>

                    <!-- Quick Actions -->
                    <div class="mt-8 border-t pt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Quick Actions</h4>
                        <div class="flex space-x-4">
                            <form method="POST" action="{{ route('admin.app-config.update-maintenance-mode') }}" class="inline">
                                @csrf
                                <input type="hidden" name="maintenance_mode" value="1">
                                <input type="hidden" name="maintenance_message" value="We are currently performing maintenance. Please try again later.">
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Enable Maintenance Mode
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.app-config.update-maintenance-mode') }}" class="inline">
                                @csrf
                                <input type="hidden" name="maintenance_mode" value="0">
                                <input type="hidden" name="maintenance_message" value="We are currently performing maintenance. Please try again later.">
                                <button type="submit"
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Disable Maintenance Mode
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
