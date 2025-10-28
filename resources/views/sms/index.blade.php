<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Send SMS') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Send messages to employees or custom phone numbers</p>
            </div>
            <div class="flex items-center space-x-2 text-green-600 dark:text-green-400">
                <i class="fas fa-sms text-xl"></i>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            <div id="messageAlert" class="hidden mb-6 p-4 rounded-2xl">
                <div class="flex items-center gap-3">
                    <i id="alertIcon" class="text-lg"></i>
                    <span id="alertMessage" class="text-sm font-medium"></span>
                </div>
            </div>

            <!-- Main SMS Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-6 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <i class="fas fa-sms text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold">Send Bulk SMS</h3>
                                <p class="text-green-100 text-sm">Reach multiple recipients at once</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-green-200">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-8">
                    <form id="smsForm" class="space-y-6">
                        @csrf

                        <!-- Recipient Selection Tabs -->
                        <div class="space-y-4">
                            <div class="flex border-b border-gray-200 dark:border-gray-600">
                                <button type="button"
                                        id="employeesTab"
                                        class="flex-1 py-3 px-4 text-center font-semibold border-b-2 transition-all duration-200 border-green-500 text-green-600 dark:text-green-400">
                                    <i class="fas fa-users mr-2"></i>
                                    Select Employees
                                </button>
                                <button type="button"
                                        id="manualTab"
                                        class="flex-1 py-3 px-4 text-center font-semibold border-b-2 transition-all duration-200 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-mobile-alt mr-2"></i>
                                    Manual Numbers
                                </button>
                            </div>

                            <!-- Employee Selection -->
                            <div id="employeesSection" class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
                                        <i class="fas fa-user-check text-green-500 mr-2"></i>
                                        Select Employees
                                    </label>
                                    <button type="button"
                                            id="selectAllBtn"
                                            class="text-sm text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 font-medium flex items-center gap-1">
                                        <i class="fas fa-check-double"></i>
                                        Select All
                                    </button>
                                </div>

                                <!-- Search -->
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text"
                                           id="employeeSearch"
                                           placeholder="Search employees by name or ID..."
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                </div>

                                <!-- Selected Employees -->
                                <div id="selectedEmployeesContainer" class="hidden bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-green-700 dark:text-green-300 font-semibold text-sm">
                                            Selected Employees (<span id="selectedCount">0</span>)
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                    id="clearSelected"
                                                    class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 text-sm font-medium">
                                                Clear All
                                            </button>
                                        </div>
                                    </div>
                                    <div id="selectedEmployeesList" class="flex flex-wrap gap-2">
                                        <!-- Selected employees will appear here -->
                                    </div>
                                </div>

                                <!-- Employee List -->
                                <div class="max-h-60 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-xl">
                                    <div id="employeesList" class="divide-y divide-gray-200 dark:divide-gray-600">
                                        <!-- Employees will be loaded here -->
                                        @foreach($employees as $employee)
                                            <label class="flex items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors duration-150 employee-item">
                                                <input type="checkbox"
                                                       value="{{ $employee->id }}"
                                                       class="employee-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500 dark:border-gray-600 dark:bg-gray-700">
                                                <div class="ml-3 flex items-center space-x-3 flex-1">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                        {{ substr($employee->name, 0, 1) }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-medium text-gray-900 dark:text-white truncate">
                                                            {{ $employee->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $employee->employee_id }} • {{ $employee->phone }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $employee->department ?? '—' }}
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Phone Numbers -->
                            <div id="manualSection" class="space-y-3 hidden">
                                <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
                                    <i class="fas fa-mobile-alt text-blue-500 mr-2"></i>
                                    Phone Numbers
                                </label>
                                <textarea id="manualNumbers"
                                          placeholder="Enter phone numbers separated by commas&#10;Example: +251911223344, +251922334455, +251933445566"
                                          rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200 resize-none"></textarea>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Separate multiple numbers with commas. Include country code (e.g., +251 for Ethiopia).
                                </p>
                                <div id="manualNumbersError" class="hidden p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                    <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span class="text-sm font-medium"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="space-y-3">
                            <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
                                <i class="fas fa-envelope text-purple-500 mr-2"></i>
                                Message
                            </label>
                            <div class="relative">
                                <textarea id="message"
                                          rows="6"
                                          placeholder="Type your message here..."
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200 resize-none"></textarea>
                                <div class="absolute bottom-3 right-3 flex items-center space-x-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        <span id="charCount">0</span>/160
                                    </span>
                                </div>
                            </div>
                            <div id="messageError" class="hidden p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span class="text-sm font-medium"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <button type="button"
                                    id="clearForm"
                                    class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2">
                                <i class="fas fa-eraser"></i>
                                Clear All
                            </button>

                            <button type="submit"
                                    id="sendButton"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane"></i>
                                <span id="sendButtonText">Send SMS (0 recipients)</span>
                                <div id="sendSpinner" class="hidden">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        class SmsManager {
            constructor() {
                this.selectedEmployees = new Set();
                this.recipientType = 'employees';
                this.allEmployeeIds = this.getAllEmployeeIds();
                this.init();
            }

            init() {
                this.bindEvents();
                this.updateStats();
            }

            getAllEmployeeIds() {
                const ids = [];
                document.querySelectorAll('.employee-checkbox').forEach(checkbox => {
                    ids.push(checkbox.value);
                });
                return ids;
            }

            bindEvents() {
                // Tab switching
                document.getElementById('employeesTab').addEventListener('click', () => this.switchTab('employees'));
                document.getElementById('manualTab').addEventListener('click', () => this.switchTab('manual'));

                // Employee selection
                document.getElementById('employeeSearch').addEventListener('input', (e) => this.searchEmployees(e.target.value));
                document.getElementById('selectAllBtn').addEventListener('click', () => this.toggleSelectAll());
                document.addEventListener('change', (e) => {
                    if (e.target.classList.contains('employee-checkbox')) {
                        this.toggleEmployee(e.target.value, e.target.checked);
                    }
                });

                // Manual numbers
                document.getElementById('manualNumbers').addEventListener('input', () => this.updateStats());

                // Message
                document.getElementById('message').addEventListener('input', (e) => {
                    document.getElementById('charCount').textContent = e.target.value.length;
                    this.updateStats();
                });

                // Buttons
                document.getElementById('clearSelected').addEventListener('click', () => this.clearSelectedEmployees());
                document.getElementById('clearForm').addEventListener('click', () => this.clearForm());
                document.getElementById('smsForm').addEventListener('submit', (e) => this.sendSms(e));
            }

            switchTab(tab) {
                this.recipientType = tab;

                // Update tab styles
                if (tab === 'employees') {
                    document.getElementById('employeesTab').classList.add('border-green-500', 'text-green-600', 'dark:text-green-400');
                    document.getElementById('employeesTab').classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    document.getElementById('manualTab').classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    document.getElementById('manualTab').classList.remove('border-green-500', 'text-green-600', 'dark:text-green-400');
                    document.getElementById('employeesSection').classList.remove('hidden');
                    document.getElementById('manualSection').classList.add('hidden');
                } else {
                    document.getElementById('manualTab').classList.add('border-green-500', 'text-green-600', 'dark:text-green-400');
                    document.getElementById('manualTab').classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    document.getElementById('employeesTab').classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    document.getElementById('employeesTab').classList.remove('border-green-500', 'text-green-600', 'dark:text-green-400');
                    document.getElementById('manualSection').classList.remove('hidden');
                    document.getElementById('employeesSection').classList.add('hidden');
                }

                this.updateStats();
            }

            searchEmployees(searchTerm) {
                if (searchTerm.length < 2) {
                    // Show all employees if search is empty
                    document.querySelectorAll('.employee-item').forEach(item => {
                        item.style.display = 'flex';
                    });
                    return;
                }

                const term = searchTerm.toLowerCase();
                document.querySelectorAll('.employee-item').forEach(item => {
                    const name = item.querySelector('.font-medium').textContent.toLowerCase();
                    const employeeId = item.querySelector('.text-sm.text-gray-500').textContent.toLowerCase();

                    if (name.includes(term) || employeeId.includes(term)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            toggleSelectAll() {
                const allSelected = this.selectedEmployees.size === this.allEmployeeIds.length;

                if (allSelected) {
                    // If all are selected, deselect all
                    this.clearSelectedEmployees();
                } else {
                    // Select all visible employees
                    const visibleEmployees = this.getVisibleEmployeeIds();
                    this.selectEmployees(visibleEmployees);
                }
            }

            getVisibleEmployeeIds() {
                const visibleIds = [];
                document.querySelectorAll('.employee-item').forEach(item => {
                    if (item.style.display !== 'none') {
                        const checkbox = item.querySelector('.employee-checkbox');
                        if (checkbox) {
                            visibleIds.push(checkbox.value);
                        }
                    }
                });
                return visibleIds;
            }

            selectEmployees(employeeIds) {
                employeeIds.forEach(employeeId => {
                    this.selectedEmployees.add(employeeId);
                    const checkbox = document.querySelector(`.employee-checkbox[value="${employeeId}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
                this.updateSelectedEmployeesDisplay();
                this.updateStats();
            }

            toggleEmployee(employeeId, isChecked) {
                if (isChecked) {
                    this.selectedEmployees.add(employeeId);
                } else {
                    this.selectedEmployees.delete(employeeId);
                }
                this.updateSelectedEmployeesDisplay();
                this.updateStats();
            }

            updateSelectedEmployeesDisplay() {
                const container = document.getElementById('selectedEmployeesContainer');
                const list = document.getElementById('selectedEmployeesList');
                const count = document.getElementById('selectedCount');
                const selectAllBtn = document.getElementById('selectAllBtn');

                if (this.selectedEmployees.size > 0) {
                    container.classList.remove('hidden');
                    count.textContent = this.selectedEmployees.size;

                    // Update Select All button text
                    const allSelected = this.selectedEmployees.size === this.allEmployeeIds.length;
                    selectAllBtn.innerHTML = allSelected ?
                        '<i class="fas fa-times"></i> Deselect All' :
                        '<i class="fas fa-check-double"></i> Select All';

                    // list.innerHTML = '';
                    // this.selectedEmployees.forEach(employeeId => {
                    //     const employeeItem = document.querySelector(`.employee-checkbox[value="${employeeId}"]`).closest('.employee-item');
                    //     const name = employeeItem.querySelector('.font-medium').textContent;

                    //     const badge = document.createElement('span');
                    //     badge.className = 'inline-flex items-center gap-1 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-300 px-3 py-1 rounded-full text-sm';
                    //     badge.innerHTML = `
                    //         ${name}
                    //         <button type="button" class="hover:text-green-900 dark:hover:text-green-100" onclick="smsManager.toggleEmployee('${employeeId}', false)">
                    //             <i class="fas fa-times text-xs"></i>
                    //         </button>
                    //     `;
                    //     list.appendChild(badge);
                    // });
                } else {
                    container.classList.add('hidden');
                    selectAllBtn.innerHTML = '<i class="fas fa-check-double"></i> Select All';
                }
            }

            clearSelectedEmployees() {
                this.selectedEmployees.clear();
                document.querySelectorAll('.employee-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                this.updateSelectedEmployeesDisplay();
                this.updateStats();
            }

            clearForm() {
                this.selectedEmployees.clear();
                document.querySelectorAll('.employee-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                document.getElementById('manualNumbers').value = '';
                document.getElementById('message').value = '';
                document.getElementById('employeeSearch').value = '';
                document.getElementById('charCount').textContent = '0';

                this.updateSelectedEmployeesDisplay();
                this.updateStats();
                this.hideErrors();
            }

            updateStats() {
                let totalRecipients = 0;

                if (this.recipientType === 'employees') {
                    totalRecipients = this.selectedEmployees.size;
                } else {
                    const numbers = document.getElementById('manualNumbers').value;
                    const numberArray = numbers.split(',').filter(num => num.trim().length > 0);
                    totalRecipients = numberArray.length;
                }

                const message = document.getElementById('message').value;
                const messagePages = Math.ceil(message.length / 160);
                const totalSms = totalRecipients * messagePages;

                document.getElementById('sendButtonText').textContent = `Send SMS (${totalRecipients} recipients)`;
            }

            hideErrors() {
                document.getElementById('messageError').classList.add('hidden');
                document.getElementById('manualNumbersError').classList.add('hidden');
                document.getElementById('messageAlert').classList.add('hidden');
            }

            showAlert(message, type = 'success') {
                const alert = document.getElementById('messageAlert');
                const icon = document.getElementById('alertIcon');
                const messageEl = document.getElementById('alertMessage');

                alert.className = `mb-6 p-4 rounded-2xl ${type === 'success' ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800'}`;
                icon.className = `${type === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-times-circle text-red-500'} text-lg`;
                messageEl.textContent = message;
                messageEl.className = `text-sm font-medium ${type === 'success' ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'}`;

                alert.classList.remove('hidden');

                // Auto hide after 5 seconds
                setTimeout(() => {
                    alert.classList.add('hidden');
                }, 5000);
            }

            showError(elementId, message) {
                const errorEl = document.getElementById(elementId);
                errorEl.querySelector('span').textContent = message;
                errorEl.classList.remove('hidden');
            }

            async sendSms(e) {
                e.preventDefault();

                this.hideErrors();

                // Validation
                const message = document.getElementById('message').value.trim();
                if (!message) {
                    this.showError('messageError', 'Please enter a message.');
                    return;
                }

                let formData = new FormData();
                formData.append('recipient_type', this.recipientType);
                formData.append('message', message);

                if (this.recipientType === 'employees') {
                    if (this.selectedEmployees.size === 0) {
                        this.showAlert('Please select at least one employee.', 'error');
                        return;
                    }
                    this.selectedEmployees.forEach(employeeId => {
                        formData.append('selected_employees[]', employeeId);
                    });
                } else {
                    const manualNumbers = document.getElementById('manualNumbers').value.trim();
                    if (!manualNumbers) {
                        this.showError('manualNumbersError', 'Please enter at least one phone number.');
                        return;
                    }
                    formData.append('manual_numbers', manualNumbers);
                }

                // Show loading state
                const sendButton = document.getElementById('sendButton');
                const sendButtonText = document.getElementById('sendButtonText');
                const sendSpinner = document.getElementById('sendSpinner');

                sendButton.disabled = true;
                sendButtonText.classList.add('hidden');
                sendSpinner.classList.remove('hidden');

                try {
                    const response = await fetch('{{ route("sms.send") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.showAlert(data.message, 'success');
                        if (data.fail_count === 0) {
                            this.clearForm();
                        }
                    } else {
                        if (data.errors) {
                            // Handle validation errors
                            Object.keys(data.errors).forEach(field => {
                                const errorMessage = data.errors[field][0];
                                if (field === 'selected_employees') {
                                    this.showAlert(errorMessage, 'error');
                                } else if (field === 'manual_numbers') {
                                    this.showError('manualNumbersError', errorMessage);
                                } else if (field === 'message') {
                                    this.showError('messageError', errorMessage);
                                }
                            });
                        } else {
                            this.showAlert(data.message, 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showAlert('An error occurred while sending SMS. Please try again.', 'error');
                } finally {
                    // Restore button state
                    sendButton.disabled = false;
                    sendButtonText.classList.remove('hidden');
                    sendSpinner.classList.add('hidden');
                }
            }
        }

        // Initialize the SMS manager when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            window.smsManager = new SmsManager();
        });
    </script>
</x-app-layout>
