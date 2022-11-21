<?php

/**
 * Useful classes to speed up dev
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// TODO: Add option to rewrite slug to avoid conflicts eg. 'event' may not be a good post type name but is a good slug
// TODO: Dopes ti work with existing post types, see the source

/**
 * Class to register a CPT and add Taxonomies to it.
 * @see https://github.com/jjgrainger/wp-custom-post-type-class/blob/master/src/CPT.php for inspiration
 */
class customPostType {

  /**
   * Holds the singular name of the post type.
   *
   * @var string $singular Post type singular name.
   */
  public $singular;

  /**
   * Holds the plural name of the post type.
   *
   * @var string $plural Post type plural name.
   */
  public $plural;

  /**
   * Holds the menu_icon of the post type.
   *
   * @var string $plural Post type menu icon.
   */
  public $icon;

  /**
   * Taxonomies
   *
   * @var array $taxonomies Holds an array of taxonomies associated with the post type.
   */
  public $taxonomies;

  /**
   * Existing taxonomies to be registered after the post type has been registered
   *
   * @var array $existing_taxonomies holds existing taxonomies
   */
  public $existing_taxonomies;

  /**
   * Taxonomy settings, an array of the taxonomies associated with the post
   * type and their options used when registering the taxonomies.
   *
   * @var array $taxonomy_settings Holds the taxonomy settings.
   */
  public $taxonomy_settings;

  /**
   * User submitted options for the post type.
   *
   * @var array $options Holds the user submitted post type options.
   */
  public $options;

  /**
   * Post type slug. This is a robot friendly name lowercase and hyphenated.
   *
   * @var string $slug Holds the post type slug name.
   */
  public $slug;

  /**
   * Taxonomy filters. Defines which filters are to appear on admin edit
   * screen used in add_taxonmy_filters().
   *
   * @var array $filters Taxonomy filters.
   */
  public $filters;

  /**
   * Defines which columns are to appear on the admin edit screen used
   * in add_admin_columns().
   *
   * @var array $columns Columns visible in admin edit screen.
   */
  public $columns;

  /**
   * User defined functions to populate admin columns.
   *
   * @var array $custom_populate_columns User functions to populate columns.
   */
  public $custom_populate_columns;

  /**
   * Sortable columns.
   *
   * @var array $sortable Define which columns are sortable on the admin edit screen.
   */
  public $sortable;

  public function __construct($singular, $plural = null, $icon = null, $options = array()) {

    if (post_type_exists($singular)) {
      $this->slug = $singular;
      $postType = get_post_type_object(get_post_type($singular));
      $this->singular = $postType->labels->singular_name;
      $this->plural   = $plural;
    } else {
      $this->singular = $singular;
      $this->plural   = $plural;
      $this->slug = $this->get_slug($singular);
      if (strpos($icon, "<svg") === 0) {
        $this->icon = 'data:image/svg+xml;base64,' . base64_encode($icon);
      } else {
        $this->icon = $icon;
      }
    }

    // Assign user submitted options to object properties

    $this->options  = $options;

    // Register Taxonomies
    add_action('init', array($this, 'register_taxonomies'));

    // Register Post Type
    add_action('init', array($this, 'register_post_type'));

    // Register Existing Taxonomies
    add_action('init', array($this, 'register_existing_taxonomies'));

    // Add taxonomy to admin edit columns.
    add_filter('manage_edit-' . $this->slug . '_columns', array($this, 'add_admin_columns'));

    // Populate the taxonomy columns with the posts terms.
    add_action('manage_' . $this->slug . '_posts_custom_column', array($this, 'populate_admin_columns'), 10, 2);

    // Add filter select option to admin edit.
    add_action('restrict_manage_posts', array($this, 'add_taxonomy_filters'));

    // Rewrite post update messages
    add_filter('post_updated_messages', array($this, 'updated_messages'));
    add_filter('bulk_post_updated_messages', array($this, 'bulk_updated_messages'), 10, 2);
  }

  /**
   * Creates a URL friendly slug for the post type
   */
  function get_slug($singular) {
    return sanitize_title($singular);
  }

  /**
   * Register Post Type
   *
   * @see https://developer.wordpress.org/reference/functions/register_post_type/
   */
  function register_post_type() {

    $plural = $this->plural;
    $singular = $this->singular;

    $labels = array(
      'name'                     => sprintf(__('%s', 'jellypress'), $plural),
      'singular_name'            => sprintf(__('%s', 'jellypress'), $singular),
      'add_new'                  => __('Add New', 'jellypress'),
      'add_new_item'             => sprintf(__('Add New %s', 'jellypress'), $singular),
      'edit_item'                => sprintf(__('Edit %s', 'jellypress'), $singular),
      'new_item'                 => sprintf(__('New %s', 'jellypress'), $singular),
      'view_item'                => sprintf(__('View %s', 'jellypress'), $singular),
      'view_items'               => sprintf(__('View %s', 'jellypress'), $plural),
      'search_items'             => sprintf(__('Search %s', 'jellypress'), $plural),
      'not_found'                => sprintf(__('No %s found', 'jellypress'), $plural),
      'not_found_in_trash'       => sprintf(__('No %s found in trask', 'jellypress'), $plural),
      'parent_item_colon'        => sprintf(__('Parent %s', 'jellypress'), $singular),
      'all_items'                => sprintf(__('All %s', 'jellypress'), $plural),
      'archives'                 => sprintf(__('%s Archives', 'jellypress'), $singular),
      'attributes'               => sprintf(__('%s Attributes', 'jellypress'), $singular),
      'uploaded_to_this_item'    => sprintf(__('Uploaded to this %s', 'jellypress'), $singular),
      'filter_items_list'        => sprintf(__('Filter %s', 'jellypress'), $plural),
      'items_list_navigation'    => sprintf(__('%s list navigation', 'jellypress'), $plural),
      'items_list'               => sprintf(__('%s list', 'jellypress'), $plural),
      'item_published'           => sprintf(__('%s published', 'jellypress'), $singular),
      'item_published_privately' => sprintf(__('%s published privately', 'jellypress'), $singular),
      'item_reverted_to_draft'   => sprintf(__('%s reverted to draft', 'jellypress'), $singular),
      'item_scheduled'           => sprintf(__('%s scheduled', 'jellypress'), $singular),
      'item_updated'             => sprintf(__('%s updated', 'jellypress'), $singular),
      'item_link'                => sprintf(__('%s Link', 'jellypress'), $singular),
      'item_link_description'    => sprintf(__('A link to a %s', 'jellypress'), $singular),
    );

    $defaults = array(
      'labels' => $labels,
      'public' => true,
      'menu_icon' => $this->icon,
      'menu_position' => 5,
      'supports' => array(
        'title',
        'editor'
      )
    );

    // Merge user-submitted options with defaults
    $options = array_replace_recursive($defaults, $this->options);

    // Update the classes options to match the merged options.
    $this->options = $options;

    // Register the CPT
    if (!post_type_exists($this->slug)) {
      register_post_type($this->slug, $options);
    }
  }

  /**
   * A quick method to add support to a post type, for example to add support for thumbnail
   * @see https://developer.wordpress.org/reference/functions/register_post_type/#supports
   *
   * @param string/array $supports supported item(s) to add
   * @return array Full list of $args assigned to post
   */
  public function add_support($supports) {


    if (post_type_exists($this->slug)) {
      add_action('init', function () use ($supports) {
        if (is_array($supports)) {
          foreach ($supports as $supported_item) {
            add_post_type_support($this->slug, $supported_item);
          }
        } else {
          add_post_type_support($this->slug, $supports);
        }
      });
    } else {
      add_filter('register_post_type_args', function ($args, $post_type) use ($supports) {

        // Bail if not updating this post type
        if ($post_type !== $this->slug) {
          return $args;
        }

        if (is_array($supports)) {
          foreach ($supports as $supported_item) {
            if (!in_array($supported_item, $args['supports'])) {
              $args['supports'][] = $supported_item;
            }
          }
        } elseif (!in_array($supports, $args['supports'])) {
          $args['supports'][] = $supports;
        }

        return $args;
      }, 10, 2);
    }
  }

  /**
   * A quick method to remove support from a post type, for example to remove the editor
   * @see https://developer.wordpress.org/reference/functions/register_post_type/#supports
   *
   * @param string/array $supports supported item(s) to remove
   * @return array Full list of $args assigned to post
   */
  public function remove_support($supports) {

    // We need a different method if the post type already exists, eg. post / page
    if (post_type_exists($this->slug)) {
      add_action('init', function () use ($supports) {
        if (is_array($supports)) {
          foreach ($supports as $supported_item) {
            remove_post_type_support($this->slug, $supported_item);
          }
        } else {
          remove_post_type_support($this->slug, $supports);
        }
      });
    } else {
      add_filter('register_post_type_args', function ($args, $post_type) use ($supports) {

        // Bail if not updating this post type
        if ($post_type !== $this->slug) {
          return $args;
        }

        if (is_array($supports)) {
          foreach ($supports as $supported_item) {
            if (in_array($supported_item, $args['supports'])) {
              $key = array_search($supported_item, $args['supports']);
              unset($args['supports'][$key]);
            }
          }
        } elseif (in_array($supports, $args['supports'])) {
          $key = array_search($supports, $args['supports']);
          unset($args['supports'][$key]);
        }

        return $args;
      }, 15, 2);
    }
  }

  /**
   * Method to display an admin message on the All posts page for this post type.
   * Useful for display information about this post type
   * @see https://developer.wordpress.org/reference/hooks/admin_notices/
   *
   * @param string $message Message to display
   * @return markup Echos out a message below the H1
   */
  public function add_admin_message($message) {

    add_action('admin_notices', function () use ($message) {
      global $pagenow;
      if (($pagenow == 'edit.php') && ($_GET['post_type'] == $this->slug)) {
        echo '<div class="notice custom-notice notice-info"><p>' . __($message, 'jellypress') . '</p></div>';
      }
    });
  }

  /**
   * Register taxonomy
   *
   * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
   *
   * @param string $singular Singular name for the taxonomy
   * @param string $plural Plural name for the taxonomy
   * @param array  $options Taxonomy options.
   */
  function register_taxonomy($singular, $plural = null, $options = array()) {

    // Check if taxonomy exists, eg. if assigning 'category' to a CPT

    $taxonomy_name = $this->get_slug($singular);

    if (!taxonomy_exists($taxonomy_name)) {

      // Default labels.
      $labels = array(
        'name'                       => sprintf(__('%s', 'jellypress'), $plural),
        'singular_name'              => sprintf(__('%s', 'jellypress'), $singular),
        'menu_name'                  => sprintf(__('%s', 'jellypress'), $plural),
        'all_items'                  => sprintf(__('All %s', 'jellypress'), $plural),
        'edit_item'                  => sprintf(__('Edit %s', 'jellypress'), $singular),
        'view_item'                  => sprintf(__('View %s', 'jellypress'), $singular),
        'update_item'                => sprintf(__('Update %s', 'jellypress'), $singular),
        'add_new_item'               => sprintf(__('Add New %s', 'jellypress'), $singular),
        'new_item_name'              => sprintf(__('New %s Name', 'jellypress'), $singular),
        'parent_item'                => sprintf(__('Parent %s', 'jellypress'), $plural),
        'parent_item_colon'          => sprintf(__('Parent %s:', 'jellypress'), $plural),
        'search_items'               => sprintf(__('Search %s', 'jellypress'), $plural),
        'popular_items'              => sprintf(__('Popular %s', 'jellypress'), $plural),
        'separate_items_with_commas' => sprintf(__('Seperate %s with commas', 'jellypress'), $plural),
        'add_or_remove_items'        => sprintf(__('Add or remove %s', 'jellypress'), $plural),
        'choose_from_most_used'      => sprintf(__('Choose from most used %s', 'jellypress'), $plural),
        'not_found'                  => sprintf(__('No %s found', 'jellypress'), $plural),
      );

      // Default options.
      $defaults = array(
        'labels' => $labels,
        'hierarchical' => false,

        'rewrite' => array(
          'slug' => $this->slug . '/' . $taxonomy_name,
          // 'with_front' => true,
          // 'hierarchical' => true,
        )
      );

      // Merge default options with user submitted options.
      $options = array_replace_recursive($defaults, $options);
    }

    // Add the taxonomy to the object array, this is used to add columns and filters to admin panel.
    $this->taxonomies[] = $taxonomy_name;

    // Create array used when registering taxonomies.
    $this->taxonomy_settings[$taxonomy_name] = $options;
  }

  /**
   * Register taxonomies
   *
   * Cycles through taxonomies added with the class and registers them.
   */
  function register_taxonomies() {

    if (is_array($this->taxonomy_settings)) {

      // Foreach taxonomy registered with the post type.
      foreach ($this->taxonomy_settings as $taxonomy_name => $options) {

        // Register the taxonomy if it doesn't exist.
        if (!taxonomy_exists($taxonomy_name)) {

          // Register the taxonomy with Wordpress
          register_taxonomy($taxonomy_name, $this->slug, $options);
        } else {

          // If taxonomy exists, register it later with register_existing_taxonomies
          $this->existing_taxonomies[] = $taxonomy_name;
        }
      }
    }
  }

  /**
   * Register Existing Taxonomies
   *
   * Cycles through existing taxonomies and registers them after the post type has been registered
   */
  function register_existing_taxonomies() {
    if (is_array($this->existing_taxonomies)) {
      foreach ($this->existing_taxonomies as $taxonomy_name) {
        register_taxonomy_for_object_type($taxonomy_name, $this->slug);
      }
    }
  }

  /**
   * Add admin columns
   *
   * Adds columns to the admin edit screen. Function is used with add_action
   * @see https://developer.wordpress.org/reference/hooks/manage_screen-id_columns/
   *
   * @param array $columns Columns to be added to the admin edit screen.
   * @return array
   */
  function add_admin_columns($columns) {

    // If no user columns have been specified, add taxonomies
    if (!isset($this->columns)) {

      $new_columns = array();

      // determine which column to add custom taxonomies after
      if (is_array($this->taxonomies) && in_array('post_tag', $this->taxonomies) || $this->slug === 'post') {
        $after = 'tags';
      } elseif (is_array($this->taxonomies) && in_array('category', $this->taxonomies) || $this->slug === 'post') {
        $after = 'categories';
      } elseif (post_type_supports($this->slug, 'author')) {
        $after = 'author';
      } else {
        $after = 'title';
      }

      // foreach existing columns
      foreach ($columns as $key => $title) {

        // add existing column to the new column array
        $new_columns[$key] = $title;

        // we want to add taxonomy columns after a specific column
        if ($key === $after) {

          // If there are taxonomies registered to the post type.
          if (is_array($this->taxonomies)) {

            // Create a column for each taxonomy.
            foreach ($this->taxonomies as $tax) {

              // WordPress adds Categories and Tags automatically, ignore these
              if ($tax !== 'category' && $tax !== 'post_tag') {
                // Get the taxonomy object for labels.
                $taxonomy_object = get_taxonomy($tax);

                // Column key is the slug, value is friendly name.
                $new_columns[$tax] = sprintf(__('%s', 'jellypress'), $taxonomy_object->labels->name);
              }
            }
          }
        }
      }

      // overide with new columns
      $columns = $new_columns;
    } else {
      // Use user submitted columns, these are defined using the object columns() method.
      $columns = $this->columns;
    }

    return $columns;
  }

  /**
   * Populate admin columns
   *
   * Populate custom columns on the admin edit screen.
   *
   * @param string $column The name of the column.
   * @param integer $post_id The post ID.
   */
  function populate_admin_columns($column, $post_id) {

    // Get wordpress $post object.
    global $post;

    // determine the column
    switch ($column) {

        // If column is a taxonomy associated with the post type.
      case (taxonomy_exists($column)):

        // Get the taxonomy for the post
        $terms = get_the_terms($post_id, $column);

        // If we have terms.
        if (!empty($terms)) {

          $output = array();

          // Loop through each term, linking to the 'edit posts' page for the specific term.
          foreach ($terms as $term) {

            // Output is an array of terms associated with the post.
            $output[] = sprintf(

              // Define link.
              '<a href="%s">%s</a>',

              // Create filter url.
              esc_url(add_query_arg(array('post_type' => $post->post_type, $column => $term->slug), 'edit.php')),

              // Create friendly term name.
              esc_html(sanitize_term_field('name', $term->name, $term->term_id, $column, 'display'))
            );
          }

          // Join the terms, separating them with a comma.
          echo join(', ', $output);

          // If no terms found.
        } else {

          // Get the taxonomy object for labels
          $taxonomy_object = get_taxonomy($column);

          // Echo no terms.
          printf(__('No %s', 'jellypress'), $taxonomy_object->labels->name);
        }

        break;

        // If column is for the post ID.
      case 'post_id':

        echo $post->ID;

        break;

        // if the column is prepended with 'meta_', this will automagically retrieve the meta values and display them.
      case (preg_match('/^meta_/', $column) ? true : false):

        // meta_book_author (meta key = book_author)
        $x = substr($column, 5);

        $meta = get_post_meta($post->ID, $x);

        echo join(", ", $meta);

        break;

        // If the column is post thumbnail.
      case 'icon':

        // Create the edit link.
        $link = esc_url(add_query_arg(array('post' => $post->ID, 'action' => 'edit'), 'post.php'));

        // If it post has a featured image.
        if (has_post_thumbnail()) {

          // Display post featured image with edit link.
          echo '<a href="' . $link . '">';
          the_post_thumbnail(array(60, 60));
          echo '</a>';
        } else {

          // Display default media image with link.
          echo '<a href="' . $link . '"><img src="' . site_url('/wp-includes/images/crystal/default.png') . '" alt="' . $post->post_title . '" /></a>';
        }

        break;

        // Default case checks if the column has a user function, this is most commonly used for custom fields.
      default:

        // If there are user custom columns to populate.
        if (isset($this->custom_populate_columns) && is_array($this->custom_populate_columns)) {

          // If this column has a user submitted function to run.
          if (isset($this->custom_populate_columns[$column]) && is_callable($this->custom_populate_columns[$column])) {

            // Run the function.
            call_user_func_array($this->custom_populate_columns[$column], array($column, $post));
          }
        }

        break;
    } // end switch( $column )
  }

  /**
   * Filters
   *
   * User function to define which taxonomy filters to display on the admin page.
   *
   * @param array $filters An array of taxonomy filters to display.
   */
  function filters($filters = array()) {

    $this->filters = $filters;
  }

  /**
   *  Add taxtonomy filters
   *
   * Creates select fields for filtering posts by taxonomies on admin edit screen.
   */
  function add_taxonomy_filters() {

    global $typenow;
    global $wp_query;

    // Must set this to the post type you want the filter(s) displayed on.
    if ($typenow == $this->slug) {

      // if custom filters are defined use those
      if (is_array($this->filters)) {

        $filters = $this->filters;

        // else default to use all taxonomies associated with the post
      } else {

        $filters = $this->taxonomies;
      }

      if (!empty($filters)) {

        // Foreach of the taxonomies we want to create filters for...
        foreach ($filters as $tax_slug) {

          // ...object for taxonomy, doesn't contain the terms.
          $tax = get_taxonomy($tax_slug);

          // Get taxonomy terms and order by name.
          $args = array(
            'orderby' => 'name',
            'hide_empty' => false
          );

          // Get taxonomy terms.
          $terms = get_terms($tax_slug, $args);

          // If we have terms.
          if ($terms) {

            // Set up select box.
            printf(' &nbsp;<select name="%s" class="postform">', $tax_slug);

            // Default show all.
            printf('<option value="0">%s</option>', sprintf(__('Show all %s', 'jellypress'), $tax->label));

            // Foreach term create an option field...
            foreach ($terms as $term) {

              // ...if filtered by this term make it selected.
              if (isset($_GET[$tax_slug]) && $_GET[$tax_slug] === $term->slug) {

                printf('<option value="%s" selected="selected">%s (%s)</option>', $term->slug, $term->name, $term->count);

                // ...create option for taxonomy.
              } else {

                printf('<option value="%s">%s (%s)</option>', $term->slug, $term->name, $term->count);
              }
            }
            // End the select field.
            print('</select>&nbsp;');
          }
        }
      }
    }
  }

  /**
   * Columns
   *
   * Choose columns to be displayed on the admin edit screen.
   *
   * @param array $columns An array of columns to be displayed.
   */
  function columns($columns) {

    // If columns is set.
    if (isset($columns)) {

      // Assign user submitted columns to object.
      $this->columns = $columns;
    }
  }

  /**
   * Populate columns
   *
   * Define what and how to populate a speicific admin column.
   *
   * @param string $column_name The name of the column to populate.
   * @param mixed $callback An anonyous function or callable array to call when populating the column.
   */
  function populate_column($column_name, $callback) {

    $this->custom_populate_columns[$column_name] = $callback;
  }

  /**
   * Sortable
   *
   * Define what columns are sortable in the admin edit screen.
   *
   * @param array $columns An array of columns that are sortable.
   */
  function sortable($columns = array()) {

    // Assign user defined sortable columns to object variable.
    $this->sortable = $columns;

    // Run filter to make columns sortable.
    add_filter('manage_edit-' . $this->slug . '_sortable_columns', array(&$this, 'make_columns_sortable'));

    // Run action that sorts columns on request.
    add_action('load-edit.php', array(&$this, 'load_edit'));
  }

  /**
   * Make columns sortable
   *
   * Internal function that adds user defined sortable columns to WordPress default columns.
   *
   * @param array $columns Columns to be sortable.
   *
   */
  function make_columns_sortable($columns) {

    // For each sortable column.
    foreach ($this->sortable as $column => $values) {

      // Make an array to merge into wordpress sortable columns.
      $sortable_columns[$column] = $values[0];
    }

    // Merge sortable columns array into wordpress sortable columns.
    $columns = array_merge($sortable_columns, $columns);

    return $columns;
  }

  /**
   * Load edit
   *
   * Sort columns only on the edit.php page when requested.
   *
   * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/request
   */
  function load_edit() {

    // Run filter to sort columns when requested
    add_filter('request', array(&$this, 'sort_columns'));
  }

  /**
   * Sort columns
   *
   * Internal function that sorts columns on request.
   *
   * @see load_edit()
   *
   * @param array $vars The query vars submitted by user.
   * @return array A sorted array.
   */
  function sort_columns($vars) {

    // Cycle through all sortable columns submitted by the user
    foreach ($this->sortable as $column => $values) {

      // Retrieve the meta key from the user submitted array of sortable columns
      $meta_key = $values[0];

      // If the meta_key is a taxonomy
      if (taxonomy_exists($meta_key)) {

        // Sort by taxonomy.
        $key = "taxonomy";
      } else {

        // else by meta key.
        $key = "meta_key";
      }

      // If the optional parameter is set and is set to true
      if (isset($values[1]) && true === $values[1]) {

        // Vaules needed to be ordered by integer value
        $orderby = 'meta_value_num';
      } else {

        // Values are to be order by string value
        $orderby = 'meta_value';
      }

      // Check if we're viewing this post type
      if (isset($vars['post_type']) && $this->slug == $vars['post_type']) {

        // find the meta key we want to order posts by
        if (isset($vars['orderby']) && $meta_key == $vars['orderby']) {

          // Merge the query vars with our custom variables
          $vars = array_merge(
            $vars,
            array(
              'meta_key' => $meta_key,
              'orderby' => $orderby
            )
          );
        }
      }
    }
    return $vars;
  }

  /**
   * Updated messages
   *
   * Internal function that modifies the post type names in updated messages
   *
   * @param array $messages an array of post updated messages
   */
  function updated_messages($messages) {

    $post = get_post();
    $singular = $this->singular;

    $messages[$this->slug] = array(
      0 => '',
      1 => sprintf(__('%s updated.', 'jellypress'), $singular),
      2 => __('Custom field updated.', 'jellypress'),
      3 => __('Custom field deleted.', 'jellypress'),
      4 => sprintf(__('%s updated.', 'jellypress'), $singular),
      5 => isset($_GET['revision']) ? sprintf(__('%2$s restored to revision from %1$s', 'jellypress'), wp_post_revision_title((int) $_GET['revision'], false), $singular) : false,
      6 => sprintf(__('%s updated.', 'jellypress'), $singular),
      7 => sprintf(__('%s saved.', 'jellypress'), $singular),
      8 => sprintf(__('%s submitted.', 'jellypress'), $singular),
      9 => sprintf(
        __('%2$s scheduled for: <strong>%1$s</strong>.', 'jellypress'),
        date_i18n(__('M j, Y @ G:i', 'jellypress'), strtotime($post->post_date)),
        $singular
      ),
      10 => sprintf(__('%s draft updated.', 'jellypress'), $singular),
    );

    return $messages;
  }

  /**
   * Bulk updated messages
   *
   * Internal function that modifies the post type names in bulk updated messages
   *
   * @param array $messages an array of bulk updated messages
   */
  function bulk_updated_messages($bulk_messages, $bulk_counts) {

    $singular = $this->singular;
    $plural = $this->plural;

    $bulk_messages[$this->slug] = array(
      'updated'   => _n('%s ' . $singular . ' updated.', '%s ' . $plural . ' updated.', $bulk_counts['updated']),
      'locked'    => _n('%s ' . $singular . ' not updated, somebody is editing it.', '%s ' . $plural . ' not updated, somebody is editing them.', $bulk_counts['locked']),
      'deleted'   => _n('%s ' . $singular . ' permanently deleted.', '%s ' . $plural . ' permanently deleted.', $bulk_counts['deleted']),
      'trashed'   => _n('%s ' . $singular . ' moved to the Trash.', '%s ' . $plural . ' moved to the Trash.', $bulk_counts['trashed']),
      'untrashed' => _n('%s ' . $singular . ' restored from the Trash.', '%s ' . $plural . ' restored from the Trash.', $bulk_counts['untrashed']),
    );

    return $bulk_messages;
  }
}
