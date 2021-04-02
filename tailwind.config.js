module.exports = {
  purge: [
      './resources/**/*.blade.php',
      './resources/**/*.js',
      './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
        fontFamily: {
            'body': ['Roboto'],
            'poppins' : ['Poppins'],
            'courgette' : ['Courgette'],
        },
        backgroundOpacity:{
            '85': '0.85',
            '90': '0.9',
        },
        boxShadow: {
            pi: '0 0 3px rgb(0 0 0 / 20%)'
        },
        backgroundImage: theme => ({

        }),
        width: {
            'screen-50': '50vw',
            88: '88%',
            90: '90%',
            95: '95%',
        },
        height: {
            '2.6': '2.6rem',
            'cal-64': 'calc(100vh - 64px)'
        },
        spacing:{
            '1.4': '1.4rem',
            '2.8': '2.8rem',
            '4.2': '4.2rem',
        },
        zIndex: {
            '-1': '-1'
        }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
      require('@tailwindcss/forms'),
  ],
}
