import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                lunar: {
                    50:  '#f8f8fa',
                    100: '#e8e8ee',
                    200: '#c8c8d4',
                    300: '#a0a0b4',
                    400: '#787890',
                    500: '#585870',
                    600: '#404058',
                    700: '#2c2c44',
                    800: '#1e1e32',
                    900: '#141420',
                    950: '#0a0a14',
                },
            },
            backgroundImage: {
                'crater-ring': 'radial-gradient(circle, transparent 60%, rgba(255,255,255,0.04) 70%, transparent 80%)',
                'lunar-surface': 'radial-gradient(ellipse at 20% 80%, rgba(30,30,50,0.8) 0%, transparent 50%), radial-gradient(ellipse at 80% 20%, rgba(20,20,40,0.6) 0%, transparent 50%)',
            },
            boxShadow: {
                'crater': 'inset 0 2px 6px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.05)',
                'glow-blue': '0 0 20px rgba(59,130,246,0.3)',
                'glow-silver': '0 0 20px rgba(200,200,220,0.2)',
            },
        },
    },
    plugins: [],
};
