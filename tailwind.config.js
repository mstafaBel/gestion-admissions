import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
        './app/View/**/*.php',
    ],

    safelist: [
        // Badges de statuts dynamiques (admissions, lits, rôles utilisateurs, etc.)
        {
            pattern: /(bg|text|ring|border)-(red|emerald|amber|sky|cyan|indigo|purple|teal|slate|rose)-(50|100|200|300|500|600|700|800|900)/,
        },
        // Variantes d'opacité (sidebars colorées)
        {
            pattern: /(bg|text|border)-(cyan|teal|sky|indigo|red|amber)-(200|300|800|900)\/(20|30|40|50|60|70)/,
        },
        // Gradients de profils patients (M/F) et en-têtes
        'from-pink-400', 'to-rose-500',
        'from-sky-400', 'to-indigo-500',
        'from-indigo-500', 'to-purple-500',
        'from-teal-500', 'to-teal-600',
        'from-indigo-500', 'to-indigo-600',
        'from-teal-900', 'to-slate-900',
        'from-cyan-500', 'to-cyan-600',
        'from-cyan-900', 'to-slate-900',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
