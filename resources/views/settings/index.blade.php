<x-app-layout>
    <div class="py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-6 py-4 rounded-xl mb-8 shadow-sm flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-lg mr-3"></i>
                    <div>
                        <p class="font-medium">Success!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-6 py-4 rounded-xl mb-8 shadow-sm flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-lg mr-3"></i>
                    <div>
                        <p class="font-medium">Error!</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- AFRO SMS Configuration Card -->
            <div class="w-full grid grid-cols-1 md:grid-cols-1 gap-6">
                <div>
                    <div
                        class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm">
                                        <i class="fas fa-sms text-2xl"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-3xl font-bold">GEEZ SMS Settings</h2>
                                        <p class="text-blue-100 mt-1">Configure your SMS gateway integration</p>
                                    </div>
                                </div>
                                <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-sm">
                                    <i class="fas fa-mobile-alt text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-8">
                            <form action="{{ route('settings.update.geez') }}" method="POST" class="space-y-8">
                                @csrf
                                @method('PUT')

                                <!-- API Configuration Section -->
                                <div class="grid grid-cols-1 gap-8">
                                    <!-- API Key -->
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-key text-blue-500 mr-2 text-sm"></i>
                                           SHORTCODE ID
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="GEEZ_SMS_SHORTCODE_ID"
                                                value="{{ old('GEEZ_SMS_SHORTCODE_ID', $settings['GEEZ_SMS_SHORTCODE_ID']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter your API key">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('GEEZ_SMS_SHORTCODE_ID')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Identifier ID -->
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-id-card text-purple-500 mr-2 text-sm"></i>
                                            GEEZ SMS TOKEN
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="GEEZ_SMS_TOKEN"
                                                value="{{ old('GEEZ_SMS_TOKEN', $settings['GEEZ_SMS_TOKEN']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter identifier ID">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user-tag text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('GEEZ_SMS_TOKEN')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Sender Configuration Section -->
                                {{-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- Sender Name -->
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-signature text-green-500 mr-2 text-sm"></i>
                                            Sender Name
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="AFRO_SENDER_NAME"
                                                value="{{ old('AFRO_SENDER_NAME', $settings['AFRO_SENDER_NAME']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter sender name">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('AFRO_SENDER_NAME')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Short Code -->
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-hashtag text-orange-500 mr-2 text-sm"></i>
                                            Short Code
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="SHORT_CODE"
                                                value="{{ old('SHORT_CODE', $settings['SHORT_CODE']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter short code">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-code text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('SHORT_CODE')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div> --}}

                                <!-- URL Configuration -->
                                <div class="space-y-2">
                                    <label
                                        class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                        <i class="fas fa-link text-indigo-500 mr-2 text-sm"></i>
                                        Base URL
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="GEEZ_SMS_BASE_URL"
                                            value="{{ old('GEEZ_SMS_BASE_URL', $settings['GEEZ_SMS_BASE_URL']) }}"
                                            class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                            placeholder="Enter base URL">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-globe text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('GEEZ_SMS_BASE_URL')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- OTP Configuration Section -->
                                <div class="grid grid-cols-1  gap-8">
                                    <!-- OTP Length -->
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-ruler-horizontal text-yellow-500 mr-2 text-sm"></i>
                                           OTP TTL MINUTES
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="OTP_TTL_MINUTES"
                                                value="{{ old('OTP_TTL_MINUTES', $settings['OTP_TTL_MINUTES']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter OTP length">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-text-width text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('OTP_TTL_MINUTES')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <div
                                        class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                            Make sure all settings are correct before updating
                                        </div>
                                        <button type="submit"
                                            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                                            <i class="fas fa-save"></i>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- Test SMS Modal -->
                    <div id="testSmsModal"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95">
                            <!-- Modal Header -->
                            <div class="bg-gradient-to-r from-green-600 to-green-900 p-6 rounded-t-2xl text-white">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-white/20 p-2 rounded-xl">
                                            <i class="fas fa-sms text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold">Test SMS Configuration</h3>
                                            <p class="text-red-100 text-sm">Send a test message to verify settings</p>
                                        </div>
                                    </div>
                                    <button onclick="closeTestModal()"
                                        class="text-white hover:text-red-200 transition-colors">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-6 space-y-6">
                                <!-- Phone Number Input -->
                                <div class="space-y-2">
                                    <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
                                        <i class="fas fa-phone text-red-500 mr-2 text-sm"></i>
                                        Phone Number
                                    </label>
                                    <div class="relative">
                                        <input type="tel" id="testPhoneNumber"
                                            class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                            placeholder="+251911223344" pattern="\+[0-9\s\-\(\)]+">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-mobile-alt text-gray-400"></i>
                                        </div>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">Include country code (e.g.,
                                        +251
                                        for Ethiopia)</p>
                                </div>

                                <!-- Message Input -->
                                <div class="space-y-2">
                                    <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
                                        <i class="fas fa-envelope text-orange-500 mr-2 text-sm"></i>
                                        Test Message
                                    </label>
                                    <div class="relative">
                                        <textarea id="testMessage"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200 resize-none"
                                            rows="4" placeholder="Enter your test message here...">SMS Test: Your configuration is working correctly! 🎉</textarea>
                                        {{-- <div class="absolute top-3 left-3 pointer-events-none">
                                            <i class="fas fa-comment text-gray-400"></i>
                                        </div> --}}
                                    </div>
                                    <div
                                        class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                                        <span>Character count: <span id="charCount">0</span></span>
                                        <span>Max: 160 characters</span>
                                    </div>
                                </div>

                                <!-- Test Result Message -->
                                <div id="testResult" class="hidden p-4 rounded-lg text-sm"></div>
                            </div>

                            <!-- Modal Footer -->
                            <div
                                class="bg-gray-50 dark:bg-gray-700 px-6 py-4 rounded-b-2xl border-t border-gray-200 dark:border-gray-600">
                                <div class="flex justify-end space-x-3">
                                    <button onclick="closeTestModal()"
                                        class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                        Cancel
                                    </button>
                                    <button onclick="sendTestSMS()" id="sendTestButton"
                                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>Send Test SMS</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div>
                    <div
                        class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm">
                                        <i class="fas fa-sms text-2xl"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-3xl font-bold">AFRO SMS Settings</h2>
                                        <p class="text-blue-100 mt-1">Configure your SMS gateway integration</p>
                                    </div>
                                </div>
                                <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-sm">
                                    <i class="fas fa-mobile-alt text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <form action="{{ route('settings.update.afro') }}" method="POST" class="space-y-8">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-key text-blue-500 mr-2 text-sm"></i>
                                            API Key
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="AFRO_API_KEY"
                                                value="{{ old('AFRO_API_KEY', $settings['AFRO_API_KEY']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter your API key">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('AFRO_API_KEY')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-id-card text-purple-500 mr-2 text-sm"></i>
                                            Identifier ID
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="AFRO_IDENTIFIER_ID"
                                                value="{{ old('AFRO_IDENTIFIER_ID', $settings['AFRO_IDENTIFIER_ID']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter identifier ID">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user-tag text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('AFRO_IDENTIFIER_ID')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-signature text-green-500 mr-2 text-sm"></i>
                                            Sender Name
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="AFRO_SENDER_NAME"
                                                value="{{ old('AFRO_SENDER_NAME', $settings['AFRO_SENDER_NAME']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter sender name">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('AFRO_SENDER_NAME')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-hashtag text-orange-500 mr-2 text-sm"></i>
                                            Short Code
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="SHORT_CODE"
                                                value="{{ old('SHORT_CODE', $settings['SHORT_CODE']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter short code">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-code text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('SHORT_CODE')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                        <i class="fas fa-link text-indigo-500 mr-2 text-sm"></i>
                                        Base URL
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="AFRO_BASE_URL"
                                            value="{{ old('AFRO_BASE_URL', $settings['AFRO_BASE_URL']) }}"
                                            class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                            placeholder="Enter base URL">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-globe text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('AFRO_BASE_URL')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-clock text-red-500 mr-2 text-sm"></i>
                                            OTP Expiration (seconds)
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="AFRO_OTP_EXPIRES_IN_SECONDS"
                                                value="{{ old('AFRO_OTP_EXPIRES_IN_SECONDS', $settings['AFRO_OTP_EXPIRES_IN_SECONDS']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter expiration time">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-hourglass-half text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('AFRO_OTP_EXPIRES_IN_SECONDS')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                            <i class="fas fa-ruler-horizontal text-yellow-500 mr-2 text-sm"></i>
                                            OTP Length
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="AFRO_OPT_LENGTH"
                                                value="{{ old('AFRO_OPT_LENGTH', $settings['AFRO_OPT_LENGTH']) }}"
                                                class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                                placeholder="Enter OTP length">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-text-width text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('AFRO_OPT_LENGTH')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <div
                                        class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                            Make sure all settings are correct before updating
                                        </div>
                                        <button type="submit"
                                            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                                            <i class="fas fa-save"></i>
                                            <span>Update</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                </div> --}}
            </div>
              <!-- Caution Section with Test Button -->
                    <div
                        class="mt-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-6">
                        <div class="flex items-start space-x-4">
                            <div class="bg-red-100 dark:bg-red-800 p-3 rounded-xl">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-red-800 dark:text-red-300">Caution!</h3>
                                    <button onclick="openTestModal()"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center space-x-2">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>Test SMS</span>
                                    </button>
                                </div>
                                <p class="text-red-700 dark:text-red-400 text-sm">
                                    <strong>Warning:</strong> Incorrect configuration will prevent SMS services from
                                    working.
                                    Ensure all credentials are accurate and valid. Double-check your API keys and
                                    settings
                                    before saving changes. Never share your credentials with unauthorized parties.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Test SMS Modal -->
                    <div id="testSmsModal"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95">
                            <!-- Modal Header -->
                            <div class="bg-gradient-to-r from-red-600 to-orange-600 p-6 rounded-t-2xl text-white">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-white/20 p-2 rounded-xl">
                                            <i class="fas fa-sms text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold">Test SMS Configuration</h3>
                                            <p class="text-red-100 text-sm">Send a test message to verify settings</p>
                                        </div>
                                    </div>
                                    <button onclick="closeTestModal()"
                                        class="text-white hover:text-red-200 transition-colors">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-6 space-y-6">
                                <!-- Phone Number Input -->
                                <div class="space-y-2">
                                    <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
                                        <i class="fas fa-phone text-red-500 mr-2 text-sm"></i>
                                        Phone Number
                                    </label>
                                    <div class="relative">
                                        <input type="tel" id="testPhoneNumber"
                                            class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                            placeholder="+251911223344" pattern="\+[0-9\s\-\(\)]+">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-mobile-alt text-gray-400"></i>
                                        </div>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">Include country code (e.g.,
                                        +251
                                        for Ethiopia)</p>
                                </div>

                                <!-- Message Input -->
                                <div class="space-y-2">
                                    <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
                                        <i class="fas fa-envelope text-orange-500 mr-2 text-sm"></i>
                                        Test Message
                                    </label>
                                    <div class="relative">
                                        <textarea id="testMessage"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-gray-200 transition-all duration-200 resize-none"
                                            rows="4" placeholder="Enter your test message here...">SMS Test: Your configuration is working correctly! 🎉</textarea>
                                        <div class="absolute top-3 left-3 pointer-events-none">
                                            <i class="fas fa-comment text-gray-400"></i>
                                        </div>
                                    </div>
                                    <div
                                        class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                                        <span>Character count: <span id="charCount">0</span></span>
                                        <span>Max: 160 characters</span>
                                    </div>
                                </div>

                                <!-- Test Result Message -->
                                <div id="testResult" class="hidden p-4 rounded-lg text-sm"></div>
                            </div>

                            <!-- Modal Footer -->
                            <div
                                class="bg-gray-50 dark:bg-gray-700 px-6 py-4 rounded-b-2xl border-t border-gray-200 dark:border-gray-600">
                                <div class="flex justify-end space-x-3">
                                    <button onclick="closeTestModal()"
                                        class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                        Cancel
                                    </button>
                                    <button onclick="sendTestSMS()" id="sendTestButton"
                                        class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>Send Test SMS</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

            <script>
                // Modal functions
                function openTestModal() {
                    const modal = document.getElementById('testSmsModal');
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modal.querySelector('.transform').classList.remove('scale-95');
                        modal.querySelector('.transform').classList.add('scale-100');
                    }, 10);
                    updateCharCount();
                }

                function closeTestModal() {
                    const modal = document.getElementById('testSmsModal');
                    modal.querySelector('.transform').classList.remove('scale-100');
                    modal.querySelector('.transform').classList.add('scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        resetTestResult();
                    }, 200);
                }

                // Character count update
                function updateCharCount() {
                    const message = document.getElementById('testMessage');
                    const charCount = document.getElementById('charCount');
                    charCount.textContent = message.value.length;

                    if (message.value.length > 160) {
                        charCount.classList.add('text-red-600', 'font-bold');
                    } else {
                        charCount.classList.remove('text-red-600', 'font-bold');
                    }
                }

                // Reset test result
                function resetTestResult() {
                    const resultDiv = document.getElementById('testResult');
                    resultDiv.classList.add('hidden');
                    resultDiv.className = 'hidden p-4 rounded-lg text-sm';
                    const sendButton = document.getElementById('sendTestButton');
                    sendButton.disabled = false;
                    sendButton.innerHTML = '<i class="fas fa-paper-plane"></i><span>Send Test SMS</span>';
                }

                // Send test SMS function
                function sendTestSMS() {
                    const phoneNumber = document.getElementById('testPhoneNumber').value;
                    const message = document.getElementById('testMessage').value;
                    const resultDiv = document.getElementById('testResult');
                    const sendButton = document.getElementById('sendTestButton');

                    // Basic validation
                    if (!phoneNumber) {
                        showTestResult('Please enter a phone number', 'error');
                        return;
                    }

                    if (!message) {
                        showTestResult('Please enter a test message', 'error');
                        return;
                    }

                    if (message.length > 160) {
                        showTestResult('Message exceeds 160 characters limit', 'error');
                        return;
                    }

                    // Show loading state
                    showTestResult('Sending test SMS...', 'loading');
                    sendButton.disabled = true;
                    sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Sending...</span>';

                    // Create form data
                    const formData = new FormData();
                    formData.append('phone', phoneNumber);
                    formData.append('message', message);
                    formData.append('_token', '{{ csrf_token() }}');

                    // Make API call to the correct route
                    fetch('{{ route('afro.test-sms') }}', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            // if (!response.ok) {
                            //     throw new Error('Network response was not ok');
                            // }
                            console.log("Res:", response)
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showTestResult('✅ ' + (data.message ||
                                    'Test SMS sent successfully! Please check the recipient device.'), 'success');
                            } else {
                                showTestResult('❌ ' + (data.message ||
                                    'Failed to send SMS. Please check your configuration and try again.'), 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showTestResult('❌ Error: ' + error.message, 'error');
                        })
                        .finally(() => {
                            sendButton.disabled = false;
                            sendButton.innerHTML = '<i class="fas fa-paper-plane"></i><span>Send Test SMS</span>';
                        });
                }

                // Show test result
                function showTestResult(message, type) {
                    const resultDiv = document.getElementById('testResult');
                    resultDiv.innerHTML = message;
                    resultDiv.classList.remove('hidden');

                    // Set styles based on type
                    const styles = {
                        success: 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800',
                        error: 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800',
                        loading: 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800'
                    };

                    resultDiv.className = `p-4 rounded-lg text-sm ${styles[type]}`;
                }

                // Event listeners
                document.addEventListener('DOMContentLoaded', function() {
                    const messageInput = document.getElementById('testMessage');
                    messageInput.addEventListener('input', updateCharCount);

                    // Initialize character count
                    updateCharCount();

                    // Close modal when clicking outside
                    const modal = document.getElementById('testSmsModal');
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            closeTestModal();
                        }
                    });

                    // Close modal with Escape key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            closeTestModal();
                        }
                    });
                });
            </script>
        </div>
    </div>

    <style>
        input:focus,
        textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
</x-app-layout>
