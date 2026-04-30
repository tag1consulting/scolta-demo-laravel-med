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
                    50:  '#0e2040',  // deep navy  — hero / darkest accent
                    100: '#1b3860',  // navy        — primary headings
                    200: '#2c5280',  // dark blue   — body text, strong labels
                    300: '#3d6898',  // medium blue  — secondary text, hover
                    400: '#507da8',  // mid blue    — links, tertiary text
                    500: '#5f7f97',  // slate-blue  — muted / section labels
                    600: '#8dafc8',  // light blue  — dividers, inactive borders
                    700: '#c8daea',  // pale blue   — subtle hover backgrounds
                    800: '#e3edf6',  // near-white  — card backgrounds
                    900: '#f2f7fc',  // off-white   — section backgrounds
                    950: '#ffffff',  // white        — page bg, nav
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
