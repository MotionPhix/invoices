import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";
import defaultTheme from 'tailwindcss/defaultTheme';

/* @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./vendor/protonemedia/laravel-splade/lib/**/*.vue",
    "./vendor/protonemedia/laravel-splade/resources/views/**/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.vue",
    // "./app/Forms/*.php",
    // "./app/Tables/*.php",
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['v-sans', ...defaultTheme.fontFamily.sans],
        display: ['Source Serif Pro', 'Georgia', 'serif'],
        body: ['Synonym', 'system-ui', 'sans-serif'],
      },
    },
  },

  plugins: [forms, typography],
};
