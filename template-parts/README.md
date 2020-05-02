# Template Partials

This folder contains template partials which are typically large sections of content that may contain template-components or other template partials.

The `acf-flexible-content.php` file is called whenever the editor uses ACF flexible content fields to design the content on the post/page. This file calls layouts from the folder `/template-layouts`.

The `content-` files are all called by files in the root theme directory such as `single.php`, `page.php`, `search.php`

Partials are used to keep the codebase DRY and to ensure the code is more easily maintained.
