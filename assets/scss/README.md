# /scss

At it's core, Jellypress uses the [Jellyfish](https://unofficialmatt.github.io/jellyfish/) SCSS framework as a basis for styling and layout.

## SCSS Compile
The following files are compiled by Gruntfile.js, with postcss applying vendor prefixes, pxtorem and minifying the output:

- <b>Admin Stylesheet, used in WP Admin</b>: `compile/admin-style.scss` -> `dist/css/admin-style.css`
- <b>Main Stylesheet for front-end of theme</b>: `compile/main.scss` -> `style.css`
- <b>Additional Editor styles for TinyMCE</b>: `compile/editor.scss` -> `dist/css/editor-style.css`
- <b>Woocommerce specific styles - loaded when WooCommerce is active</b>: `woocommerce.scss` -> `dist/css/woocommerce.css`

## Customising the theme

For the most part, you will want to make new partials in project/{sub-folder}. Any partials found within these folders are automatically included into the _all.scss partial which is loaded into the relevant top-level `{admin-style,compile,editor,woocommerce}.scss`

Jellyfish files should not be edited. You can override the default variables with the settings file in `_settings.scss`
