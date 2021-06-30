<?php

$composer_autoload = __DIR__ . '/vendor/autoload.php';

if (file_exists($composer_autoload)) {
  require_once $composer_autoload;
  $timber = new Timber\Timber();
}

// If the Timber plugin isn't activated, print a notice in the admin.
if (!class_exists('Timber')) {
  add_action('admin_notices', function () {
    echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' .
      esc_url(admin_url('plugins.php#timber')) .
      '">' .
      esc_url(admin_url('plugins.php')) .
      '</a></p></div>';
  });
  return;
}

Timber::$dirname = ['views', 'views/components'];

// Autoescape by default. Set to false if not wanted.
// Timber::$autoescape = 'html';

// Create our version of the TimberSite object
class KindredSite extends Timber\Site
{
  public function __construct()
  {
    add_action('after_setup_theme', [$this, 'theme_supports']);
    add_filter('timber/context', [$this, 'add_to_context']);
    add_filter('timber/twig', [$this, 'add_to_twig']);
    add_action('init', [$this, 'register_post_types']);
    add_action('init', [$this, 'register_taxonomies']);
    add_action('init', [$this, 'register_menus']);
    add_action('init', [$this, 'register_widgets']);
    parent::__construct();
  }
  // Abstracting long chunks of code.

  // The following included files only need to contain the arguments and register_whatever functions. They are applied to WordPress in these functions that are hooked to init above.

  // The point of having separate files is solely to save space in this file. Think of them as a standard PHP include or require.

  public function register_post_types()
  {
    require 'lib/custom-types.php';
  }

  public function register_taxonomies()
  {
    require 'lib/taxonomies.php';
  }

  public function register_menus()
  {
    require 'lib/menus.php';
  }

  // Access data site-wide.

  // This function adds data to the global context of your site. In less-jargon-y terms, any values in this function are available on any view of your website. Anything that occurs on every page should be added here.

  public function add_to_context($context)
  {
    // Add menus to context
    foreach (get_registered_nav_menus() as $k => $v) {
      $context['menu_' . $k] = new TimberMenu($k);
    }

    // Add options to context
    $context['options'] = get_fields('option');

    // Add latest post to context
    $context['firstPost'] = Timber::get_posts(['posts_per_page' => 1]);

    // This 'site' context below allows you to access main site information like the site title or description.
    $context['site'] = $this;
    return $context;
  }

  public function theme_supports()
  {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    add_theme_support( 'title-tag' );

    /*
      Enable support for Post Thumbnails on posts and pages.
      @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
    */
    add_theme_support('post-thumbnails');

    /*
      Switch default core markup for search form, comment form, and comments
      to output valid HTML5.
    */
    add_theme_support(
      'html5',
      array(
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
      )
    );

    add_theme_support('menus');
  }

  /** This Would return 'foo bar!'.
   *
   * @param string $text being 'foo', then returned 'foo bar!'.
   */
  public function myfoo($text)
  {
    $text .= ' bar!';
    return $text;
  }

  // Here you can add your own fuctions to Twig. Don't worry about this section if you don't come across a need for it.
  // See more here: http://twig.sensiolabs.org/doc/advanced.html
  public function add_to_twig($twig)
  {
    $twig->addExtension(new Twig_Extension_StringLoader());
    return $twig;
  }
}

new KindredSite();

/*
  Kindred theme functions
*/

// Enqueue scripts
function kindred_scripts()
{
  // Use jQuery from a CDN
  wp_deregister_script('jquery');
  wp_register_script(
    'jquery',
    '//ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js',
    [],
    null,
    true
  );

  // Enqueue our stylesheet and JS file with a jQuery dependency.
  // Note that we aren't using WordPress' default style.css, and instead enqueueing the file of compiled Sass.
  wp_enqueue_style('styles', get_template_directory_uri() . '/dist/css/bundle.css', 1.0);
  wp_enqueue_script('scripts', get_template_directory_uri() . '/dist/js/bundle.js', '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'kindred_scripts');

function kindred_admin_style()
{
  wp_register_style(
    'kindred_admin_styles',
    get_template_directory_uri() . '/dist/css/admin.css',
    false,
    '1.0.0'
  );
  wp_enqueue_style('kindred_admin_styles');
}
add_action('admin_enqueue_scripts', 'kindred_admin_style');

require_once 'lib/clean-head.php';

// Add theme settings
if (function_exists('acf_add_options_page')) {
  acf_add_options_page('Theme Settings');
}

// Editor changes
require_once 'lib/blocks.php';

require_once 'lib/login.php';

// Create Gutenberg blocks based on configuration in twig files in views/blocks
require_once 'lib/timber-acf-wp-blocks.php';

// Enqueue Google fonts
// function kindred_google_fonts()
// {
//   $query_args = [
//     'family' => 'Montserrat:400,700,Lora:400,700',
//     'subset' => 'latin,latin-ext',
//   ];
//   wp_register_style(
//     'kindred_google_fonts',
//     add_query_arg($query_args, '//fonts.googleapis.com/css'),
//     [],
//     null
//   );
// }

// add_action('wp_enqueue_scripts', 'kindred_google_fonts');

function kindred_block_categories($categories, $post)
{
  // if ($post->post_type !== 'post') {
  //     return $categories;
  // }
  return array_merge($categories, [
    [
      'slug' => 'kindred',
      'title' => __('kindred Blocks', 'kindred'),
    ],
  ]);
}
add_filter('block_categories', 'kindred_block_categories', 10, 2);
