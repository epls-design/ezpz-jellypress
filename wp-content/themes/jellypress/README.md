# Jellypress Wordpress Boilerplate Theme

## EPLS Design

<p>
<img src="https://img.shields.io/github/stars/epls-design/ezpz-jellypress.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/github/issues/epls-design/ezpz-jellypress.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/maintenance/yes/2020.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/github/commit-activity/y/epls-design/ezpz-jellypress.svg?style=flat-square&logo=github"/>
<img src="https://img.shields.io/github/last-commit/epls-design/ezpz-jellypress.svg?style=flat-square&logo=github"/>
</p>

## About Jellypress

Jellypress is a Wordpress starter theme based on [underscores](https://github.com/Automattic/_s) and designed to work with [Jellyfish SCSS framework](https://github.com/unofficialmatt/jellyfish) by [Matt Weet](https://github.com/unofficialmatt).

This theme, like `underscores` is made for hacking and use as a standalone theme, rather than a Parent Theme.

REQUIRES php 7.4+

### Included in `Jellypress`...

- A just right amount of lean, well-commented, modern, HTML5 templates using the Wordpress templating engine and best practices
- Custom template tags in `inc/template-tags.php` and `inc/dry.php` that keep your templates clean and neat and prevent code duplication.
- AJAX loading of posts in archives.
- Full support for `WooCommerce plugin` integration with hooks in `inc/woocommerce.php`, styling override woocommerce.css with product gallery features (zoom, swipe, lightbox) enabled.
- Lots of other custom functions and helpers. Take a look around the `/inc` folder
- Automatic language notation if you use the proper Wordpress hooks eg. `_e('Translate this', 'textdomain')` or `echo __('Translate this', 'textdomain')`
- Support for ACF and baked-in use of ACF flexible content for page layout using flexible content fields. A range of 'out of the box' layout options:
  - Cards
  - Countdown timer
  - Cover
  - Aria accessibly FAQS that also generate rich Schema markup
  - Image gallery
  - Unfiltered HTML
  - iFrame
  - Image
  - Linked Posts
  - Magic Column layouts
  - Map using Google Maps API
  - Number Counter
  - Post Archive
  - Slider using [SwiperJS](https://swiperjs.com)
  - Testimonials using [SwiperJS](https://swiperjs.com)
  - Text
  - Text Columns
  - Video
- Optimised loading of JS scripts by only enqueuing them when they are needed on page

### Plus, all of the awesome benefits from [Jellyfish](https://unofficialmatt.github.io/jellyfish/):

- Manage dependencies with [NPM](https://www.npmjs.com/)
- [Gulp.js](https://gulpjs.com/) to run tasks and make your life easier!
- [BrowserSync](https://www.browsersync.io/) to live reload your browser when any changes are detected - works great with [Local by Flywheel](https://localbyflywheel.com/)!
- Lots of customisation options through \_settings.scss

## Getting Started

It is recommended to develop locally with [Local by Flywheel](https://localbyflywheel.com/) which makes setting up a new Wordpress installation a breeze. Even more so if you use a Blueprint.

1. Make sure that you have npm, browsersync and Gulp installed on your system following the guides on their respective websites. You only need to do this once.

2. Decide on the following naming conventions:

   - Theme friendly name eg. `My Awesome Theme`
   - Theme slug eg. `myawesometheme` - also used as the Text Domain

3. Duplicate the `env_example.json` to `env.json` and modify the DEV_URL to fit your development environment - this is used by the Gulp php server as a proxy

4. Search for: `'jellypress'` (inside single quotations) and replace with your theme slug e.g. `'myawesometheme'` to capture the text domain. Search for: `@package jellypress` and replace with your theme slug e.g. `@package myawesometheme` to capture text domains in file headers.

5. Search for: `jellypress_` and replace with your theme slug e.g. `myawesometheme_` to capture all the php function names.

6. Search for: `jellypress-` and replace with your theme slug e.g. `myawesometheme-` to capture prefixed handles.

7. Update `package.json`:

- Rename `friendly_name` to your Theme Friendly Name
- Rename `text_domain` to your Theme Text Domain
- Update `description`, `homepage`, `repository` and `bugs`

8. Install the dev dependencies:

```bash
npm install
```

9. Rebuild the theme and start Gulp:

```bash
gulp init
```

10. Do a final check to see if there are any stragglers named `jellypress` - and amend as required

11. Check the front end to ensure the theme has installed properly.

12. Perform a `git commit` in the root Wordpress directory.

13. Alternatively, run the bash script and this will all be done for you ;)

## Contribution guidelines

Please update the README guidelines any time key changes are made to the theme boilerplate.

- The SCSS directory structure follows ITCSS standards. See the [Jellyfish docs](https://github.com/unofficialmatt/jellyfish) for information.

## Some tips and usage notes

- Where possible you should use Wordpress localization for displaying text that is hard coded into the theme.
- If you know the theme does not require some of the built in blocks, it's good practice to remove them from ACF so as to not overwhelm the client. We can also reinstate these from core later if required. They are good to have in the back pocket!

## Who do I talk to?

The best person to talk to is Matt [matt@epls.design](matt@epls.design) as Jellyfish and Jellypress were both originally written by him
