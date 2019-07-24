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
2. Search for: `jellypress_` and replace with: `theme-name_` to capture all the function names.
3. Search for: `Text Domain: jellypress` and replace with: `Text Domain: theme-name` in `style.css`.
4. Search for: <code>&nbsp;_s</code> and replace with: <code>&nbsp;Theme Name</code> (with a space before it) to capture DocBlocks.
5. Search for: `jellypress-` and replace with: `theme-name-` to capture prefixed handles.

Then, update the stylesheet header in `scss/config/_themeinfo.scss`, the links in `footer.php` with your own information and rename `_s.pot` from `languages` folder to use the theme's slug.

Finally, add in your Jellyfish scss (currently not bundled with Jellypress, as still in development), update the Gruntfile.js and npm packages to suit your theme and start hacking away!

