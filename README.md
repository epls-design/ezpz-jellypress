Jellypress
===
(https://github.com/unofficialmatt/jellyfish)
Jellypress is a Wordpress starter based on [underscores](https://github.com/Automattic/_s) and designed to work with [Jellyfish CSS framework](https://github.com/unofficialmatt/jellyfish)

This theme, like `underscores` is made for hacking and use as a standalone theme, rather than a Parent Theme.

Included:
* A just right amount of lean, well-commented, modern, HTML5 templates.
* A helpful 404 template.
* Custom template tags in `inc/template-tags.php` that keep your templates clean and neat and prevent code duplication.
* Some small tweaks in `inc/template-functions.php` that can improve your theming experience.

Getting Started
---------------

Clone the repo into your Wordpress themes directory and rename the folder into the name of your theme. You'll then need to do a five-step find and replace on the name in all the templates.

1. Search for: `'jellypress'` and replace with: `'theme-name'` to capture the text domain.
2. Search for: `jellypress_` and replace with: `theme_name_` to capture all the function names.
3. Search for: ` _jellypress` (include the space) and replace with: ` Theme_Name` (with a space before it) to capture DocBlocks.
4. Search for: `jellypress-` and replace with: `theme-name-` to capture prefixed handles.
5. Find and replace `@package jellypress` to `@package theme-name`
6. Then, update the stylesheet header in `build/scss/config/_themeinfo.scss`, the links in `footer.php` with your own information and rename `_jellypress.pot` from `languages` folder to use the theme's slug.
7. In the theme functions.php, search for 'hide_acf_admin' and update the URLs to the live site url, to hide ACF on production.
8. Do a final check to see if there are any stragglers named `jellypress`

Finally, add in your Jellyfish scss (currently not bundled with Jellypress, as still in development), update the Gruntfile.js and npm packages to suit your theme and start hacking away!
