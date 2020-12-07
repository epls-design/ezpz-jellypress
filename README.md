# Jellypress Wordpress Start Theme
<p>
<img src="https://img.shields.io/github/stars/unofficialmatt/jellypress.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/github/issues/unofficialmatt/jellypress.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/maintenance/yes/2020.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/github/commit-activity/y/unofficialmatt/jellypress.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/github/last-commit/unofficialmatt/jellypress.svg?style=flat-square&logo=github"/>
</p>

Jellypress is a Wordpress starter theme based on [underscores](https://github.com/Automattic/_s) and designed to work with [Jellyfish SCSS framework](https://github.com/unofficialmatt/jellyfish)

This theme, like `underscores` is made for hacking and use as a standalone theme, rather than a Parent Theme.

Included in `Jellypress`...:
* A just right amount of lean, well-commented, modern, HTML5 templates.
* A helpful 404 template.
* Custom template tags in `inc/template-tags.php` that keep your templates clean and neat and prevent code duplication.
* Some small tweaks in `inc/template-functions.php` that can improve your theming experience.
* Full support for `WooCommerce plugin` integration with hooks in `inc/woocommerce.php`, styling override woocommerce.css with product gallery features (zoom, swipe, lightbox) enabled.
* Lots of other custom functions and helpers. Take a look around the `/inc` folder
* Support for ACF and baked-in use of ACF flexible content for page layout

Plus, all of the awesome benefits from [Jellyfish](https://unofficialmatt.github.io/jellyfish/):
* Manage dependencies with [NPM](https://www.npmjs.com/)
* [Grunt.js](https://gruntjs.com/) to run tasks and make your life easier!
* [BrowserSync](https://www.browsersync.io/) to live reload your browser when any changes are detected - works great with [Local by Flywheel](https://localbyflywheel.com/)!
* Lots of customisation options through _settings.scss

## Getting Started

Make sure that you have npm, browsersync and grunt installed on your system following the guides on their respective websites. Then, clone the project into your required folder with git:

```bash
git clone https://github.com/unofficialmatt/jellypress.git
cd jellypress
```

Rename the folder `jellypress` to the title of your theme e.g. `my-awesome-client` . You'll now need to do a eight-step checklist on the name in all the templates:

1. Search for: `'jellypress'` (inside single quotations) and replace with: `'my-awesome-client'` to capture the text domain.
2. Search for: `jellypress_` and replace with: `my_awesome_client_` to capture all the function names.
3. Search for: `Text Domain: jellypress` and replace with: `Text Domain: my-awesome-client` in `style.css`.
4. Search for: <code>&nbsp;jellypress</code> (with a space before it) and replace with: <code>&nbsp;My_Awesome_Client</code> to capture DocBlocks.
5. Search for: `jellypress-` and replace with: `my-awesome-client-` to capture prefixed handles.
6. Then, update the stylesheet header in `assets/scss/config/_themeinfo.scss`, the links in `footer.php` with your own information and rename `jellypress.pot` from `languages` folder to use the theme's slug.
7. In the theme functions.php, search for 'hide_acf_admin' and update the URLs to the live site url, to hide ACF on production.
8. Do a final check to see if there are any stragglers named `jellypress`

Install the dev dependencies:

```bash
npm install
```

Finally, start grunt:

```bash
grunt
```
