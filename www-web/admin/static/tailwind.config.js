/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["../pages/**/*.{html,js,tpl}"],
  theme: {
    extend: {
      backgroundImage: {
        'main-logo': "url('images/logo.png')",   
        'login-splash': "url('images/login.png')",
      }
    }
  },
  plugins: [],
};
