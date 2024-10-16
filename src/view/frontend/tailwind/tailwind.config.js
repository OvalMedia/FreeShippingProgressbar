module.exports = {
    content: [
        '../templates/**/*.phtml',
    ],
    theme: {
        extend: {
            keyframes: {
                fspg: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0)' },
                }
            },
            animation: {
                fspg: 'fspg 1.25s 1',
            }
        }
    }
}
