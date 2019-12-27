<?php

/*
*  Custom post type function
*/

// function custom_post_type() {

// Set UI labels for Custom Post Type
	// $labels = array(
	// 	'name'                => _x( 'Projects', 'Post Type General Name', 'kindred' ),
	// 	'singular_name'       => _x( 'Project', 'Post Type Singular Name', 'kindred' ),
	// 	'menu_name'           => __( 'Projects', 'kindred' ),
	// 	'parent_item_colon'   => __( 'Parent Project', 'kindred' ),
	// 	'all_items'           => __( 'All Projects', 'kindred' ),
	// 	'view_item'           => __( 'View Project', 'kindred' ),
	// 	'add_new_item'        => __( 'Add New Project', 'kindred' ),
	// 	'add_new'             => __( 'Add New', 'kindred' ),
	// 	'edit_item'           => __( 'Edit Project', 'kindred' ),
	// 	'update_item'         => __( 'Update Project', 'kindred' ),
	// 	'search_items'        => __( 'Search Projects', 'kindred' ),
	// 	'not_found'           => __( 'Not Found', 'kindred' ),
	// 	'not_found_in_trash'  => __( 'Not found in Trash', 'kindred' ),
	// );

// Set other options for Custom Post Type
	// $args = array(
	// 	'label'               => __( 'Project', 'kindred' ),
	// 	'description'         => __( 'Portfolio Projects', 'kindred' ),
	// 	'labels'              => $labels,
	// 	'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
	// 	'hierarchical'        => false,
	// 	'taxonomies'		  => array( 'project-category' ),
	// 	'public'              => true,
	// 	'show_ui'             => true,
	// 	'show_in_menu'        => true,
	// 	'menu_icon'           => 'dashicons-admin-customizer',
	// 	'show_in_nav_menus'   => true,
	// 	'show_in_admin_bar'   => true,
	// 	'menu_position'       => 5,
	// 	'can_export'          => true,
	// 	'has_archive'         => true,
	// 	'exclude_from_search' => true,
	// 	'publicly_queryable'  => true,
	// 	'capability_type'     => 'page',
	// );

// 	register_post_type( 'projects', $args );
// }

/*
*  Hook into the 'init' action so that the function
*  Containing our post type registration is not
*  unnecessarily executed.
*/

// add_action( 'init', 'custom_post_type', 0 );
//
// function namespace_add_custom_types( $query ) {
//   if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
// 	$query->set( 'post_type', array(
// 	 'post', 'nav_menu_item', 'Projects'
// 		));
// 	  return $query;
// 	}
// }
// add_filter( 'pre_get_posts', 'namespace_add_custom_types' );

/*
*  Add custom taxonomy for CPT
*/

// add_action( 'init', 'kindred_taxonomies', 0 );
//
// function kindred_taxonomies() {
// 	register_taxonomy(
// 		'listing_status',
// 		'Projects',
// 		array(
// 			'labels' => array(
// 				'name' => 'Listing Status',
// 				'add_new_item' => 'Add New Listing Status',
// 				'new_item_name' => "New Listing Status"
// 			),
// 			'show_ui' => true,
// 			'show_tagcloud' => false,
// 			'hierarchical' => true
// 		)
// 	);
// }
