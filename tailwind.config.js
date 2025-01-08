// const defaultTheme = require('tailwindcss/defaultTheme')
import colors from "tailwindcss/colors";

/** @type {import('tailwindcss').Config} */
export default {
  important: true,
  darkMode: ["selector", "body.body--dark"],
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx,vue}",

    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    screens: {
      xxs: '450px',
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl:'1556px',
    },
    fontFamily: {
      // sans: ['"Open Sans"', ...defaultTheme.fontFamily.sans],
    },
    extend: {
      fontSize: {
        '3xs': '8px',
        '2xs': '10px',
        '1xs': '12px',
      },
      colors: {
        primary: {
          800: "#025864",
          300: "#8ababf",
          50: "#b4c8cb38"
        },
        danger: colors.red[500],
      },
    },
  },
  plugins: [],
};