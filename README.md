## EPLS Design

# EZPZ Jellypress Wordpress Boilerplate Theme
Forked from [Jellypress](https://github.com/unofficialmatt/jellypress)

<p>
<img src="https://img.shields.io/github/stars/epls-design/ezpz-jellypress.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/github/issues/epls-design/ezpz-jellypress.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/maintenance/yes/2020.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/github/commit-activity/y/epls-design/ezpz-jellypress.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/github/last-commit/epls-design/ezpz-jellypress.svg?style=flat-square&logo=github"/>
</p>

## About Jellypress
Jellypress is a Wordpress starter theme based on [underscores](https://github.com/Automattic/_s) and designed to work with [Jellyfish SCSS framework](https://github.com/epls-design/ezpz-jellypress) by [Matt Weet](https://github.com/unofficialmatt).

This theme, like `underscores` is made for hacking and use as a standalone theme, rather than a Parent Theme.

### Included in `Jellypress`...

* A just right amount of lean, well-commented, modern, HTML5 templates using the Wordpress templating engine and best practices
* A helpful 404 template.
* Custom template tags in `inc/template-tags.php` and `inc/dry.php` that keep your templates clean and neat and prevent code duplication.
* Support for registering custom post types in `inc/post_types`.
* Support for setting user capabilities in `inc/user_caps`.
* AJAX loading of posts in archives.
* Lazy loading of background images with `assets/js/site/lazyload-bg.js`.
* Full support for `WooCommerce plugin` integration with hooks in `inc/woocommerce.php`, styling override woocommerce.css with product gallery features (zoom, swipe, lightbox) enabled.
* Lots of other custom functions and helpers. Take a look around the `/inc` folder
* SEO support and rich schema mark up using ACF for data input
* Automatic language translation if you use the proper Wordpress hooks eg. `_e('Translate this', 'textdomain')` or `echo __('Translate this', 'textdomain')`
* Bundled with recommended and necessary plugins through [TGM Plugin Activation](http://tgmpluginactivation.com/)
* Support for ACF and baked-in use of ACF flexible content for page layout using flexible content fields. A range of 'out of the box' layout options:
  * Cards
  * Charts with charts.js
  * Countdown timer
  * Cover
  * Aria accessibly FAQS that also generate rich Schema markup
  * Image gallery
  * Unfiltered HTML
  * iFrame
  * Image
  * Image compare using TwentyTwenty.js
  * Linked Posts
  * Magic Column layouts
  * Map using Google Maps API
  * Number Counter
  * Post Archive
  * Slider using Splide.js
  * Testimonials using Splide.js
  * Text
  * Text Columns
  * Video
* Optimised loading of JS scripts by only enqueuing them when they are needed on page
* .env file which is used to:
  * Set up a helpful 'developer' flag in the UI
  * Pull remote images to save local hard drive space

### Plus, all of the awesome benefits from [Jellyfish](https://unofficialmatt.github.io/jellyfish/):
* Manage dependencies with [NPM](https://www.npmjs.com/)
* [Grunt.js](https://gruntjs.com/) to run tasks and make your life easier!
* [BrowserSync](https://www.browsersync.io/) to live reload your browser when any changes are detected - works great with [Local by Flywheel](https://localbyflywheel.com/)!
* Lots of customisation options through _settings.scss


## Getting Started

It is recommended to develop locally with [Local by Flywheel](https://localbyflywheel.com/) which makes setting up a new Wordpress installation a breeze. Even more so if you use a Blueprint.

1. Once Wordpress has been installed, copy the standard .gitignore file in to the root folder `/public_html`
2. Make sure that you have npm, browsersync and grunt installed on your system following the guides on their respective websites. You only need to do this once.
3. Decide on the following naming conventions:
   * Theme friendly name eg. `My Awesome Theme`
   * Theme slug eg. `myawesometheme` - also used as the Text Domain
4. Then, clone this theme into `wp-content/themes/`, replacing `myawesometheme` with the slug of your theme:

```bash
git clone https://github.com/unofficialmatt/jellypress.git cd myawesometheme
```

5. Remove the `.git` folder from the theme, we will use the root `/public_html` as our git repository.

```bash
rm -rf .git && rm .gitignore
```

### Now it's time to modify the theme:

6. Duplicate the `env_example.json` to `env.json` and modify the settings to fit your development environment

7. Search for: `'jellypress'` (inside single quotations) and replace with your theme slug e.g. `'myawesometheme'` to capture the text domain. Search for: `@package jellypress` and replace with your theme slug e.g. `@package myawesometheme` to capture text domains in file headers.

8. Search for: `jellypress_` and replace with your theme slug e.g. `myawesometheme_` to capture all the php function names.

9. Search for: `jellypress-` and replace with your theme slug e.g. `myawesometheme-` to capture prefixed handles.

10. Rename `languages/jellypress.pot` to your theme slug e.g. `languages/myawesometheme.pot`. In `assets/grunt/translation.js` update `potFilename: 'jellypress.pot'` to match e.g. `potFilename: 'myawesometheme.pot'`

11. Update `package.json`:
  * Rename `friendly_name` to your Theme Friendly Name
  * Rename `text_domain` to your Theme Text Domain
  * Update `description`, `homepage`, `repository` and `bugs`

12. Install the dev dependencies:

```bash
npm install
```

13. Rebuild the theme and start grunt:

```bash
grunt init
```
14. Activate the theme and **make sure to install required plugins**. It is recommended to activate EZPZ WP Optimise straight away.

15. Do a final check to see if there are any stragglers named `jellypress` - and amend as required

16. Check the front end to ensure the theme has installed properly.

17. Perform a `git commit` in the root Wordpress directory.

## Contribution guidelines

Please update the README guidelines any time key changes are made to the theme boilerplate.

- The SCSS directory structure follows ITCSS standards. See the [Jellyfish docs](https://github.com/epls-design/ezpz-jellypress) for information.

## Some tips and usage notes
- Where possible you should use Wordpress localization for displaying text that is hard coded into the theme.
- It is recommended not to edit any of the files in `assets/scss/jellyfish` as this allows us to make upstream changes to the scss framework.
- You should try to keep code modularised following a simple MVC framework; for example, the scss for a card module should sit in the same folder as the view.
- If you know the theme does not require some of the built in blocks, it's good practice to remove them from ACF so as to not overwhelm the client. We can also reinstate these from core later if required. They are good to have in the back pocket!

## Known bugs
- SASS watch does not always see newly added files, particular in the `template-parts` folder, so sometimes you have to stop the grunt task and reload to see these new files.

## Who do I talk to?
The best person to talk to is Matt [matt@epls.design](matt@epls.design) as Jellyfish and Jellypress were both originally written by him
