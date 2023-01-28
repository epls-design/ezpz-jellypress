# Template Layouts

This folder contains template layouts, which are large sections of content used throughout the theme. Template Layouts might include content, hero, etc.

They differ from components in that a layout is typically a larger section of the page, potentially containing other partials such as components.

Each layout should have it's own folder within which there can be:

- Variations of the view (eg. for post type, such as hero, hero-post, hero-page, hero-event)
- SCSS stylesheet files

Partials are used to keep the codebase DRY and to ensure the code is more easily maintained.

Any SCSS files will be automatically imported into the \_\_all.scss file and ultimately the main style.css file through Gulp tasks.
