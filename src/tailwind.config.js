import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        {
            pattern: /content-\[.*\]/,
            variants: ['before', 'after', 'hover', 'group-hover'],
        },
        'bg-lightGreen', // カスタムカラーの背景
        'text-gray',     // テキストカラー
        'border-[#ECECEC]', // ボーダーカラー
        'shadow-[2px_2px_4px_rgba(96,102,108,0.5)]' // カスタムシャドウ
    ],    

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                poppins: ['Poppins', 'sans-serif'],
                roboto: ['Roboto', 'sans-serif'],
                urbanist: ['Urbanist', 'sans-serif'],
                noto: ['"Noto Sans"', 'sans-serif'],
            },
            colors: {
                lightBlue: '#EFF6F8',
                blue: '#6EC7D6',
                moreBlue: '#2F79B0',
                hoverMoreBlue: '#25628E',
                borderBlue: '#EFF6F8',
                nav: '#60666C',
                box: '#F0F0F0',
                title: '#60666C',
                bar: '#2BC7BC',
                placeholder: '#AFB2B5',
                hoverBar: '#25B0A9',
                gray: '#60666C',
                lightGreen: '#2BC7BC',
                lightGray: '#AFB2B5',
                arrowRed: '#FF8D80',
                header: '#F8F8F8',
                uncompleated: '#AFB2B5',
                uncompleatedTask: '#D2D2D2',
                feet: '#EAF9F9',
            },
            fontSize: {
                '14.5': '14.5px',
                '13': '13px',
            },
            spacing: {
                '6px': '6px',
                '14px': '14px',
                '18px': '18px',
                '21px': '21px',
                '22px': '22px',
                '26px': '26px',
                '32px': '32px',
                '184px': '184px',
                '3.1r': '3.1rem',
            },
            gap: {
                '19.5': '19.5px',
            },
            backgroundColor: {
                'bar-hover': 'rgba(0, 0, 0, 0.5)',  // カスタム背景色の定義
            },
            borderWidth: {
                '3': '3px',
                '6': '6px',
            },
            maxHeight: {
                '400': '400px',
            },
            minHeight: {
                '400': '400px',
            },
            overflow: {
                'auto-y': 'auto',
            },
            inset: {
                '1/5': '20%',
                '2/5': '42%',
                '3/5': '60%',
                '4/5': '80%',
                '1/11': '12%',
                '1/6': '20%',
            }
        },
    },
    

    plugins: [
        forms
    ],
};
