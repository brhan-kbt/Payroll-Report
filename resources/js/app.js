import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Initialize dark mode before Alpine starts
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dark mode from localStorage or system preference
    const darkMode = localStorage.getItem('darkMode') === 'true' ||
                   (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);

    if (darkMode) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('darkMode')) {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    });
});

Alpine.start();
