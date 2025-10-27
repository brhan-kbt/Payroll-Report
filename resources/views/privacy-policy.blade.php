<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    @vite('resources/css/app.css') {{-- Tailwind or your compiled CSS --}}
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <div class="flex items-center justify-center p-6">
        <div class="w-full max-w-7xl bg-white dark:bg-gray-800 shadow-md rounded-lg p-8">
            <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Privacy Policy</h1>

            <div class="prose max-w-none dark:prose-invert">
                @if($privacyPolicy && $privacyPolicy->value)
                    {!! $privacyPolicy->value !!}
                @else
                    <p class="text-gray-600 dark:text-gray-300">
                        No privacy policy has been set yet. Please contact the administrator.
                    </p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
