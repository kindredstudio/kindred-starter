<?php

// If the Timber plugin isn't activated, print a notice in the admin.
if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		} );
	return;
}


// Create our version of the TimberSite object
class StarterSite extends TimberSite {

	// This function applies some fundamental WordPress setup, as well as our functions to include custom post types and taxonomies.
	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_menus' ) );
		add_action( 'init', array( $this, 'register_widgets' ) );
		parent::__construct();
	}


	// Abstracting long chunks of code.

	// The following included files only need to contain the arguments and register_whatever functions. They are applied to WordPress in these functions that are hooked to init above.

	// The point of having separate files is solely to save space in this file. Think of them as a standard PHP include or require.

	function register_post_types(){
		require('lib/custom-types.php');
	}

	function register_taxonomies(){
		require('lib/taxonomies.php');
	}

	function register_menus(){
		require('lib/menus.php');
	}

	function register_widgets(){
		require('lib/widgets.php');
	}

	// Access data site-wide.

	// This function adds data to the global context of your site. In less-jargon-y terms, any values in this function are available on any view of your website. Anything that occurs on every page should be added here.

function add_to_context( $context ) {

	// Add menus to context
	foreach(get_registered_nav_menus() as $k => $v) {
    $context['menu_' . $k] = new TimberMenu($k);
	}

	// Add options to context
	$context['options'] = get_fields('option');

	// This 'site' context below allows you to access main site information like the site title or description.
	$context['site'] = $this;
	return $context;
}

	// Here you can add your own fuctions to Twig. Don't worry about this section if you don't come across a need for it.
	// See more here: http://twig.sensiolabs.org/doc/advanced.html
	function add_to_twig( $twig ) {
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( 'myfoo', new Twig_Filter_Function( 'myfoo' ) );
		return $twig;
	}

}

new StarterSite();

/*
 Walker theme functions
*/

// Enqueue scripts
function walker_scripts() {

	// Use jQuery from a CDN
	wp_deregister_script('jquery');
	wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', array(), null, true);

	// Enqueue our stylesheet and JS file with a jQuery dependency.
	// Note that we aren't using WordPress' default style.css, and instead enqueueing the file of compiled Sass.
	wp_enqueue_style( 'styles', get_template_directory_uri() . '/dist/css/bundle.css', 1.0);
	wp_enqueue_script( 'scripts', get_template_directory_uri() . '/dist/js/bundle.js', '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'walker_scripts' );

// Custom image sizes
add_image_size( 'tastemaker', 663, 768, true );
add_image_size( 'post_thumb', 800, 600, true );
add_image_size( 'banner', 1200, 768, true );
add_image_size( 'shop_thumb', 225, 225, true );

// require_once('lib/utils.php');

// Add theme settings
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page('Theme Settings');
}

// Disable Gutenberg on certain templates
require('lib/disable-editor.php');

// Create Gutenberg blocks based on configuration in a twig file
require('lib/timber-acf-wp-blocks.php');
