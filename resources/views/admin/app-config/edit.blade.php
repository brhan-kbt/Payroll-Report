<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit App Configuration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.app-config.update', $appConfig) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="key" class="block text-sm font-medium text-gray-700">Key</label>
                            <input type="text"
                                   id="key"
                                   name="key"
                                   value="{{ old('key', $appConfig->key) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('key') border-red-500 @enderror"
                                   required>
                            @error('key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                            <select id="type"
                                    name="type"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('type') border-red-500 @enderror"
                                    required>
                                <option value="">Select Type</option>
                                <option value="string" {{ old('type', $appConfig->type) === 'string' ? 'selected' : '' }}>String</option>
                                <option value="boolean" {{ old('type', $appConfig->type) === 'boolean' ? 'selected' : '' }}>Boolean</option>
                                <option value="integer" {{ old('type', $appConfig->type) === 'integer' ? 'selected' : '' }}>Integer</option>
                                <option value="json" {{ old('type', $appConfig->type) === 'json' ? 'selected' : '' }}>JSON</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="value" class="block text-sm font-medium text-gray-700">Value</label>
                            <div id="value-input-container">
                                @if($appConfig->type === 'boolean')
                                    <select id="value" name="value" class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('value') border-red-500 @enderror" required>
                                        <option value="true" {{ old('value', $appConfig->typed_value) ? 'selected' : '' }}>True</option>
                                        <option value="false" {{ !old('value', $appConfig->typed_value) ? 'selected' : '' }}>False</option>
                                    </select>
                                @elseif($appConfig->type === 'json')
                                    <textarea id="value"
                                              name="value"
                                              rows="5"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('value') border-red-500 @enderror"
                                              required>{{ old('value', json_encode($appConfig->typed_value, JSON_PRETTY_PRINT)) }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">Enter valid JSON format</p>
                                @else
                                    <input type="{{ $appConfig->type === 'integer' ? 'number' : 'text' }}"
                                           id="value"
                                           name="value"
                                           value="{{ old('value', $appConfig->typed_value) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('value') border-red-500 @enderror"
                                           required>
                                @endif
                            </div>
                            @error('value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $appConfig->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="is_public"
                                       value="1"
                                       {{ old('is_public', $appConfig->is_public) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-lg focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Make this configuration public (accessible via API)</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.app-config.index') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const container = document.getElementById('value-input-container');
            const currentValue = document.getElementById('value').value;

            if (type === 'boolean') {
                container.innerHTML = `
                    <select id="value" name="value" class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="true" ${currentValue === 'true' ? 'selected' : ''}>True</option>
                        <option value="false" ${currentValue === 'false' ? 'selected' : ''}>False</option>
                    </select>
                `;
            } else if (type === 'json') {
                container.innerHTML = `
                    <textarea id="value" name="value" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>${currentValue}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Enter valid JSON format</p>
                `;
            } else {
                container.innerHTML = `
                    <input type="${type === 'integer' ? 'number' : 'text'}"
                           id="value"
                           name="value"
                           value="${currentValue}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           required>
                `;
            }
        });
    </script>
</x-app-layout>
