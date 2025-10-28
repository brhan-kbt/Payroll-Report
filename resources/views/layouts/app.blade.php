<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Quill.js Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireScripts

    <style>
        /* Base Select2 styling to match Tailwind */
        .select2-container .select2-selection--single {
            height: 2.5rem;
            border-radius: 0.375rem;
            /* rounded-md */
            border: 1px solid #d1d5db;
            /* gray-300 */
            padding: 0.375rem 0.75rem;
            background-color: #ffffff;
            /* light mode bg */
            color: #1f2937;
            /* gray-800 */
            display: flex;
            align-items: center;
        }

        .select2-container .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%);
            right: 0.75rem;
        }

        .select2-dropdown {
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            padding: 0.25rem;
        }

        /* Dark mode overrides */
        .dark .select2-container .select2-selection--single {
            background-color: #374151;
            /* gray-700 */
            border-color: #4b5563;
            /* gray-600 */
            color: #e5e7eb;
            /* gray-200 */
        }

        .dark .select2-dropdown {
            background-color: #1f2937;
            /* gray-800 */
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .dark .select2-results__option--highlighted {
            background-color: #2563eb !important;
            /* indigo-600 */
            color: #ffffff !important;
        }
    </style>
</head>


<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

</body>

</html>
