/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["*.{html,js}", "./js/**/*.js", "./*.php"],
  theme: {
    
    container :{
      padding :{
        DEFAULT : '15px'
      }
    },
    screens :{
      sm : '640px',
      md : '768px',
      lg : '960px',
      xl : '1330px',
    },
    extend: {
      colors :{
        primary : '#242a2b',
        secondary : '#808080',
        assent : {
          DEFAULT : '#d39f6f',
          secondary : '#84540f',
          tertiary : '#90c6cd'
        },
        grey : '#e8f0f1',
      },
      fontFamily :{
        primary  : 'Poppins',
      },
      boxShadow : {
        custom1 : '  0 4px 9px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1)',
        custom2 : ' 0px 0px 30px  0px rgba(8 , 73 ,81 , 0.06)',
      },

      keyframes:{
        float:{
          "0% , 100%" : {transform: "translateY(0)"},
          "50%" : {transform: "translateY(-13px)"}
        },
        marquee: {
          '0%': { transform: 'translateX(0%)' },
          '100%': { transform: 'translateX(-100%)' },
        },
        marquee2: {
          '0%': { transform: 'translateX(0%)' },
          '100%': { transform: 'translateX(-100%)' },
        }
      },
      animation:{
        float : "float 3s ease-in-out infinite",
        marquee: 'marquee 25s linear infinite',
        marquee2: 'marquee2 25s linear infinite',
      }
    },
  },
  plugins: [],
}

