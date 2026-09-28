import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            screens: {
                // Small phones (iPhone SE / 320-479px) get a single column;
                // this is the first width where two columns still breathe.
                xs: '480px',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50:   '#F4F5FA',
                    100:  '#E9EAF7',
                    200:  '#CDD0EE',
                    300:  '#AAAEE4',
                    400:  '#7B81D3',
                    500:  '#5B62C5',
                    600:  '#434CB6',
                    700:  '#383F99',
                    800:  '#2F3479',
                    900:  '#262A5E',
                    950:  '#181B3E',
                },
            },
            animation: {
                'fade-in': 'fadeIn 0.3s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
        },
    },
    plugins: [forms, typography],
};
