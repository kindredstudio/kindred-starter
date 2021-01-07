<?php
/**
 * Disable Editor
 *
 * @package      ClientName
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// disable for posts
// add_filter('use_block_editor_for_post', '__return_false', 10);

/**
 * Templates and Page IDs without editor
 *
 */
function kindred_disable_editor( $id = false ) {

	$excluded_templates = array(
		 'templates/contact.php'
	);

	$excluded_ids = array(
		 get_option( 'page_on_front' )
	);

	if( empty( $id ) )
		return false;

	$id = intval( $id );
	$template = get_page_template_slug( $id );

	return in_array( $id, $excluded_ids ) || in_array( $template, $excluded_templates );
}

/**
 * Disable Gutenberg by template
 *
 */
function kindred_disable_gutenberg( $can_edit, $post_type ) {

	if( ! ( is_admin() && !empty( $_GET['post'] ) ) )
		return $can_edit;

	if( kindred_disable_editor( $_GET['post'] ) )
		$can_edit = false;

	return $can_edit;

}
add_filter( 'gutenberg_can_edit_post_type', 'kindred_disable_gutenberg', 10, 2 );
add_filter( 'use_block_editor_for_post_type', 'kindred_disable_gutenberg', 10, 2 );

/**
 * Disable Classic Editor by template
 *
 */
function kindred_disable_classic_editor() {

	$screen = get_current_screen();
	if( 'page' !== $screen->id || ! isset( $_GET['post']) )
		return;

	if( kindred_disable_editor( $_GET['post'] ) ) {
		remove_post_type_support( 'page', 'editor' );
	}

}
add_action( 'admin_head', 'kindred_disable_classic_editor' );

add_filter( 'allowed_block_types', 'kindred_allowed_block_types', 10, 2 );

function kindred_allowed_block_types( $allowed_blocks, $post ) {

	$allowed_blocks = array(
		'core/paragraph',
		'core/heading',
		'core/image',
		'core/list',
		// 'core/quote',
		'core/shortcode',
		// 'core/audio',
		// 'core/cover',
		// 'core/file',
		// 'core/video',
		// 'core/table',
		// 'core/verse',
		'core/code',
		// 'core/freeform',
		'core/html',
		// 'core/preformatted',
		'core/pullquote',
		// 'core/button',
		'core/text-columns',
		// 'core/media-text',
		// 'core/more',
		// 'core/nextpage',
		// 'core/separator',
		// 'core/spacer',
		// 'core/archives',
		// 'core/categories',
		// 'core/latest-comments',
		// 'core/latest-posts',
		// 'core/calendar',
		// 'core/rss',
		// 'core/search',
		// 'core/tag-cloud',
		// 'core/embed',
		// 'core-embed/twitter',
		// 'core-embed/youtube',
		// 'core-embed/facebook',
		// 'core-embed/instagram',
		// 'core-embed/wordpress',
		// 'core-embed/soundcloud',
		// 'core-embed/spotify',
		// 'core-embed/flickr',
		// 'core-embed/vimeo',
		// 'core-embed/animoto',
		// 'core-embed/cloudup',
		// 'core-embed/collegehumor',
		// 'core-embed/dailymotion',
		// 'core-embed/funnyordie',
		// 'core-embed/hulu',
		// 'core-embed/imgur',
		// 'core-embed/issuu',
		// 'core-embed/kickstarter',
		// 'core-embed/meetup-com',
		// 'core-embed/mixcloud',
		// 'core-embed/photobucket',
		// 'core-embed/polldaddy',
		// 'core-embed/reddit',
		// 'core-embed/reverbnation',
		// 'core-embed/screencast',
		// 'core-embed/scribd',
		// 'core-embed/slideshare',
		// 'core-embed/smugmug',
		// 'core-embed/speaker',
		// 'core-embed/ted',
		// 'core-embed/tumblr',
		// 'core-embed/videopress',
		// 'core-embed/wordpress-tv'
	);

	// if( $post->post_type === 'page' ) {
	// 	$allowed_blocks[] = 'core/shortcode';
	// }

	return $allowed_blocks;

}
