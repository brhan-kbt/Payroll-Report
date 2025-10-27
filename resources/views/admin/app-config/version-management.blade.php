<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Version Management') }}
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

            <form method="POST" action="{{ route('admin.app-config.update-version-management') }}">
                @csrf

                <!-- General Version Settings -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">General Version Settings</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="app_latest_version" class="block text-sm font-medium text-gray-700">Latest Version</label>
                                <input type="text"
                                       id="app_latest_version"
                                       name="app_latest_version"
                                       value="{{ old('app_latest_version', $versionConfigs->get('app_latest_version')->value ?? '1.0.0') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="app_min_version" class="block text-sm font-medium text-gray-700">Minimum Version</label>
                                <input type="text"
                                       id="app_min_version"
                                       name="app_min_version"
                                       value="{{ old('app_min_version', $versionConfigs->get('app_min_version')->value ?? '1.0.0') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="app_update_url" class="block text-sm font-medium text-gray-700">Update URL</label>
                                <input type="url"
                                       id="app_update_url"
                                       name="app_update_url"
                                       value="{{ old('app_update_url', $versionConfigs->get('app_update_url')->value ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                           name="app_force_update"
                                           value="1"
                                           {{ old('app_force_update', $versionConfigs->get('app_force_update')->value ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-lg focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Force Update</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Android Version Settings -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Android Version Settings</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="app_latest_version_android" class="block text-sm font-medium text-gray-700">Latest Version</label>
                                <input type="text"
                                       id="app_latest_version_android"
                                       name="app_latest_version_android"
                                       value="{{ old('app_latest_version_android', $versionConfigs->get('app_latest_version_android')->value ?? '1.0.0') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="app_min_version_android" class="block text-sm font-medium text-gray-700">Minimum Version</label>
                                <input type="text"
                                       id="app_min_version_android"
                                       name="app_min_version_android"
                                       value="{{ old('app_min_version_android', $versionConfigs->get('app_min_version_android')->value ?? '1.0.0') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="app_update_url_android" class="block text-sm font-medium text-gray-700">Update URL</label>
                                <input type="url"
                                       id="app_update_url_android"
                                       name="app_update_url_android"
                                       value="{{ old('app_update_url_android', $versionConfigs->get('app_update_url_android')->value ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                           name="app_force_update_android"
                                           value="1"
                                           {{ old('app_force_update_android', $versionConfigs->get('app_force_update_android')->value ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-lg focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Force Update</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- iOS Version Settings -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">iOS Version Settings</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="app_latest_version_ios" class="block text-sm font-medium text-gray-700">Latest Version</label>
                                <input type="text"
                                       id="app_latest_version_ios"
                                       name="app_latest_version_ios"
                                       value="{{ old('app_latest_version_ios', $versionConfigs->get('app_latest_version_ios')->value ?? '1.0.0') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="app_min_version_ios" class="block text-sm font-medium text-gray-700">Minimum Version</label>
                                <input type="text"
                                       id="app_min_version_ios"
                                       name="app_min_version_ios"
                                       value="{{ old('app_min_version_ios', $versionConfigs->get('app_min_version_ios')->value ?? '1.0.0') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="app_update_url_ios" class="block text-sm font-medium text-gray-700">Update URL</label>
                                <input type="url"
                                       id="app_update_url_ios"
                                       name="app_update_url_ios"
                                       value="{{ old('app_update_url_ios', $versionConfigs->get('app_update_url_ios')->value ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                           name="app_force_update_ios"
                                           value="1"
                                           {{ old('app_force_update_ios', $versionConfigs->get('app_force_update_ios')->value ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-lg focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Force Update</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update Version Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
