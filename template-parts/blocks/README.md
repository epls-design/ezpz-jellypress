# Template Blocks

This folder contains layouts which are called by `acf-flexible-content/view.php` when the editor is using ACF flexible content fields to create their page layout.

Directories should match the slug of the row in the flexible content field, and should be organised as such:

- view.php : Defines how the layout renders on the page
- SCSS stylesheet files

These partials will often include template parts from the `template-components` folder.

Partials are used to keep the codebase DRY and to ensure the code is more easily maintained.

Any SCSS files will be automatically imported into the _all.scss file and ultimately the main style.css file through Grunt tasks.


# TODO: Add a JS file in to test - e. countup.js
