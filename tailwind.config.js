import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                inter: ['Inter', ...defaultTheme.fontFamily.sans],
                jakarta: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                outfit: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                slate: {
                    950: '#020617',
                    900: '#0f172a',
                    800: '#1e293b',
                    // ... existing slate colors are default from tailwind
                },
                indigo: {
                    50: '#f5f7ff',
                    100: '#ebf0fe',
                    200: '#ced9fd',
                    300: '#adc0fb',
                    400: '#8da7fa',
                    500: '#6d8ef8',
                    600: '#5a76e0',
                    700: '#485eb3',
                    800: '#364786',
                    900: '#242f59',
                    950: '#12172d',
                },
                electric: {
                    50: '#f0f7ff',
                    100: '#e0effe',
                    200: '#bae0fd',
                    300: '#7cc7fb',
                    400: '#38aaf7',
                    500: '#0e8ee9',
                    600: '#0270c7',
                    700: '#0359a1',
                    800: '#074c85',
                    900: '#0c406e',
                    950: '#082949',
                },
                premium: {
                    950: '#0a0d14',
                    900: '#0f131d',
                    800: '#1a202c',
                }
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
            boxShadow: {
                'premium': '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025)',
                'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
            }
        },
    },

    plugins: [forms],
};
