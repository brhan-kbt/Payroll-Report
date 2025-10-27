<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Theme Test Page') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Theme Testing Components</h3>

                    <!-- Color Test Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-500 text-white p-4 rounded">
                            <h4 class="font-semibold">Blue Card</h4>
                            <p>This is a blue card with white text</p>
                        </div>

                        <div class="bg-green-500 text-white p-4 rounded">
                            <h4 class="font-semibold">Green Card</h4>
                            <p>This is a green card with white text</p>
                        </div>

                        <div class="bg-red-500 text-white p-4 rounded">
                            <h4 class="font-semibold">Red Card</h4>
                            <p>This is a red card with white text</p>
                        </div>

                        <div class="bg-yellow-500 text-black p-4 rounded">
                            <h4 class="font-semibold">Yellow Card</h4>
                            <p>This is a yellow card with black text</p>
                        </div>

                        <div class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 p-4 rounded border border-gray-300 dark:border-gray-600">
                            <h4 class="font-semibold">Adaptive Card</h4>
                            <p>This card adapts to the theme</p>
                        </div>

                        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-4 rounded border border-gray-200 dark:border-gray-600 shadow">
                            <h4 class="font-semibold">Shadow Card</h4>
                            <p>This card has a shadow that adapts</p>
                        </div>
                    </div>

                    <!-- Form Elements Test -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Form Elements</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Text Input</label>
                                <input type="text"
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Enter some text">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select</label>
                                <select class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                    <option>Option 1</option>
                                    <option>Option 2</option>
                                    <option>Option 3</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Textarea</label>
                                <textarea class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                          rows="3"
                                          placeholder="Enter some text"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Checkbox</label>
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded">
                                    <label class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Check this box</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Button Test -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Buttons</h4>
                        <div class="flex flex-wrap gap-3">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Primary Button
                            </button>

                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Success Button
                            </button>

                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Danger Button
                            </button>

                            <button class="bg-yellow-500 hover:bg-yellow-700 text-black font-bold py-2 px-4 rounded">
                                Warning Button
                            </button>

                            <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Secondary Button
                            </button>
                        </div>
                    </div>

                    <!-- Table Test -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Table</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">John Doe</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">john@example.com</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Active
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">Jane Smith</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">jane@example.com</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                Inactive
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Links Test -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Links</h4>
                        <div class="space-y-2">
                            <p><a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">This is a link</a></p>
                            <p><a href="#" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">This is another link</a></p>
                            <p><a href="#" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">This is a red link</a></p>
                        </div>
                    </div>

                    <!-- Text Colors Test -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Text Colors</h4>
                        <div class="space-y-2">
                            <p class="text-gray-500 dark:text-gray-400">Gray 500 text</p>
                            <p class="text-gray-600 dark:text-gray-300">Gray 600 text</p>
                            <p class="text-gray-700 dark:text-gray-200">Gray 700 text</p>
                            <p class="text-gray-800 dark:text-gray-100">Gray 800 text</p>
                            <p class="text-gray-900 dark:text-white">Gray 900 text</p>
                            <p class="text-blue-500 dark:text-blue-400">Blue text</p>
                            <p class="text-green-500 dark:text-green-400">Green text</p>
                            <p class="text-red-500 dark:text-red-400">Red text</p>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Toggle the theme using the button in the navigation to see how all elements adapt to dark and light modes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
