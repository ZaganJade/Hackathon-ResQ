import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // Typography: Poppins family
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },

            // Nature-inspired color palette
            colors: {
                // Primary: Emerald scale
                primary: {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    200: '#a7f3d0',
                    300: '#6ee7b7',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                    800: '#065f46',
                    900: '#064e3b',
                },
                // Secondary: Teal scale
                secondary: {
                    50: '#f0fdfa',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#14b8a6',
                    600: '#0d9488',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#134e4a',
                },
                // Accent: Lime scale
                accent: {
                    50: '#f7fee7',
                    100: '#ecfccb',
                    200: '#d9f99d',
                    300: '#bef264',
                    400: '#a3e635',
                    500: '#84cc16',
                    600: '#65a30d',
                    700: '#4d7c0f',
                    800: '#3f6212',
                    900: '#365314',
                },
                // Semantic colors
                success: '#059669',
                warning: '#f59e0b',
                danger: '#f43f5e',
                info: '#0ea5e9',
            },

            // Custom border radius (nature: soft, rounded)
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },

            // Custom shadows (soft, nature-tinted)
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(5, 150, 105, 0.1)',
                'soft-lg': '0 10px 40px -4px rgba(5, 150, 105, 0.15)',
                'soft-xl': '0 20px 60px -8px rgba(5, 150, 105, 0.2)',
                'card': '0 2px 12px rgba(0, 0, 0, 0.06)',
                'card-hover': '0 8px 30px rgba(5, 150, 105, 0.12)',
            },

            // Spacing scale (4px base)
            spacing: {
                '18': '4.5rem',
                '22': '5.5rem',
            },

            // Animation durations
            transitionDuration: {
                '400': '400ms',
                '600': '600ms',
                '800': '800ms',
            },

            // Custom animations - Fluid Modern Dashboard
            animation: {
                'fade-up': 'fadeUp 600ms ease-out forwards',
                'fade-in': 'fadeIn 400ms ease-out forwards',
                'blur-in': 'blurIn 600ms ease-out forwards',
                'scale-in': 'scaleIn 500ms ease-out forwards',
                'shimmer': 'shimmer 2s infinite',
                'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
                'slide-up': 'slideUp 500ms ease-out forwards',
                'bounce-soft': 'bounceSoft 2s infinite',
                'slide-in-left': 'slideInLeft 400ms cubic-bezier(0.4, 0, 0.2, 1) forwards',
                'slide-out-left': 'slideOutLeft 300ms cubic-bezier(0.4, 0, 0.2, 1) forwards',
                'float': 'float 3s ease-in-out infinite',
                'glow-pulse': 'glowPulse 2s ease-in-out infinite',
                'spin-slow': 'spin 3s linear infinite',
            },

            keyframes: {
                fadeUp: {
                    '0%': { opacity: '0', transform: 'translateY(24px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                blurIn: {
                    '0%': { opacity: '0', filter: 'blur(8px)' },
                    '100%': { opacity: '1', filter: 'blur(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                    '50%': { opacity: '0.8', transform: 'scale(1.05)' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                bounceSoft: {
                    '0%, 100%': { transform: 'translateY(-5%)' },
                    '50%': { transform: 'translateY(0)' },
                },
                slideInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideOutLeft: {
                    '0%': { opacity: '1', transform: 'translateX(0)' },
                    '100%': { opacity: '0', transform: 'translateX(-20px)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                glowPulse: {
                    '0%, 100%': { boxShadow: '0 0 20px rgba(52, 211, 153, 0.3)' },
                    '50%': { boxShadow: '0 0 40px rgba(52, 211, 153, 0.6)' },
                },
            },
        },
    },

    plugins: [forms],
};
