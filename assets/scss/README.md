# /scss

At it's core, this theme uses the [Jellyfish](https://unofficialmatt.github.io/jellyfish/) SCSS framework as a basis for styling and layout.

## SCSS Compile
The following files are compiled by Gruntfile.js, with postcss applying vendor prefixes, pxtorem and minifying the output:

- <b>Admin Stylesheet, used in WP Admin</b>: `compile/admin-style.scss` -> `dist/css/admin-style.css`
- <b>Main Stylesheet for front-end of theme</b>: `compile/main.scss` -> `style.css`
- <b>Additional Editor styles for TinyMCE</b>: `compile/editor.scss` -> `dist/css/editor-style.css`
- <b>Woocommerce specific styles - loaded when WooCommerce is active</b>: `compile/woocommerce.scss` -> `dist/css/woocommerce.css`

## Customising the theme

Please read the README file inside `/project` as this gives some guidance notes on directory structure and naming conventions - it is highly recommended to follow the [ITCSS triangle structure](https://www.xfive.co/blog/itcss-scalable-maintainable-css-architecture/) for maintaining SCSS partials.

In a nutshell:

- Files inside the `/jellyfish` directory should not be edited. This ensures that upstream changes to Jellyfish can be applied if required.
- All global variables should be defined and amended inside the `/project/02-settings` directory.
- Most of the UI styling work will take place in `/project/07-components`
- Alternatively, component CSS should be stored inside `../../template-parts/blocks`, `../../template-parts/components` or `../../template-parts/layout` for a more object-oriented approach and to more-clearly see how a component is both styled and output.

Grunt will automatically compile partials across the theme directory, to ensure that partials are loaded in a way that conforms to ITCSS.
