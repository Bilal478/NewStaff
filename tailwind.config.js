const colors = require('tailwindcss/colors');
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: colors.black,
            white: colors.white,
            gray: colors.trueGray,
            red: colors.rose,
            yellow: colors.amber,
            green: colors.emerald,
            blue: colors.lightBlue,
            indigo: colors.indigo,
            purple: colors.violet,
            navy: {
                900: '#283252'
            },
        },
        extend: {
            minWidth: {
                '24': '6rem',
                '32': '8rem',
                '36': '9rem',
                '10': '10rem',
                '44': '11rem',
                '48': '12rem',
                '52': '13rem',
            },
            width: {
                '1/8': '12.5%',
            },
            fontFamily: {
                'roboto': ['Roboto', ...defaultTheme.fontFamily.sans],
                'montserrat': ['Montserrat', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    variants: {
        extend: {
            backgroundColor: ['active'],
            textColor: ['active', 'group-focus'],
            ringColor: ['hover', 'active', 'focus'],
            scale: ['group-hover'],
        }
    },
    purge: {
        content: [
            './app/**/*.php',
            './resources/**/*.js',
            './resources/**/*.php',
        ],
        options: {
            defaultExtractor: (content) => content.match(/[\w-/.:]+(?<!:)/g) || [],
            whitelistPatterns: [/-active$/, /-enter$/, /-leave-to$/, /show$/],
        },
    },
    plugins: [
        // require('@tailwindcss/forms'),
        // require('@tailwindcss/typography'),
    ],
};
