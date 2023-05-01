# Template Components

This folder contains components that are reused throughout the website. Components are self contained blocks of content, that may contain dynamic fields from ACF eg. a card, slider etc.

Each component should have it's own folder within which there can be:

- Variations of the view (eg. for post type, such as card, card-post, card-page, card-event)
- SCSS stylesheet files

Partials are used to keep the codebase DRY and to ensure the code is more easily maintained.

Any SCSS files will be automatically imported into the \_\_all.scss file and ultimately the main style.css file through Gulp tasks.
