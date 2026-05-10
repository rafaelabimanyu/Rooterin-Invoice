const defaultTheme = require('tailwindcss/defaultTheme')
const forms = require('@tailwindcss/forms')

module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './storage/framework/views/*.php',
    ],


    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                jakarta: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                premium: {
                    950: '#0a0d14',
                    900: '#0f131d',
                    800: '#1a202c',
                    700: '#2d3748',
                }
            },

            boxShadow: {
                premium:
                    '0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.025)',
            },
        },
    },

    plugins: [forms],
}