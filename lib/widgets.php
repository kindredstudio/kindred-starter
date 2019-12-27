<?php

/*
 * Widgets and Sidebars
 */

 // Register custom widget areas.
 // function kindred_custom_widgets_init() {
 // 	register_sidebar( array(
 // 		'name'          => esc_html__( 'Footer', 'kindred' ),
 // 		'id'            => 'sidebar-2',
 // 		'description'   => esc_html__( 'Add widgets here.', 'kindred' ),
 // 		'before_widget' => '<section id="%1$s" class="widget %2$s">',
 // 		'after_widget'  => '</section>',
 // 		'before_title'  => '<h2 class="widget-title">',
 // 		'after_title'   => '</h2>',
 // 	) );
 // }
 // add_action( 'widgets_init', 'kindred_custom_widgets_init' );

 // Enable shortcodes in text widgets
 // add_filter('widget_text','do_shortcode');

 // Allow break tags in widget titles
 function html_widget_title( $title ) {
 	$title = str_replace( '[', '<', $title );
 	$title = str_replace( '[/', '</', $title );
 	$title = str_replace( 'br]', 'br>', $title );

  return $title;
 }

 add_filter( 'widget_title', 'html_widget_title' );
