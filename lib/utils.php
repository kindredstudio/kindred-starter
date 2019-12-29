<?php
/*
 * Utility functions and cleanup
 */

// Remove <p> tags around images
function _s_filter_ptags_on_images($content) {
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

add_filter('the_content', '_s_filter_ptags_on_images');
add_filter('upload_mimes', '_s_allow_svg_upload');

// Filter Yoast SEO's settings, so it's below custom metaboxes
add_filter('wpseo_metabox_prio', function () {
	return 'low';
});

// Add admin styles
function admin_style() {
	wp_enqueue_style('admin-styles', get_template_directory_uri() . '/assets/dist/admin.css');
}

add_action('admin_enqueue_scripts', 'admin_style');

// Disable code editor
function mw_remove_editor_menu() {
	remove_action('admin_menu', '_add_themes_utility_last', 101);
}

add_action('mw_admin_menu', 'mw_remove_editor_menu', 1);

// Clean up ACF WYSIWYG toolbar
function mw_acf_wysiwyg_toolbar($toolbars) {
	$toolbars['Simple'] = array();

	// Only one row of buttons
	$toolbars['Simple'][1] = array(
		'formatselect',
		'bold',
		'link',
		'italic',
		'unlink'
	);
	return $toolbars;
	}

add_filter('acf/fields/wysiwyg/toolbars', 'mw_acf_wysiwyg_toolbar');

// Make custom fields work with Yoast SEO (only impacts the light, but helpful!)
// https://imperativeideas.com/making-custom-fields-work-yoast-wordpress-seo/
if (is_admin()) {

	// add_filter('wpseo_pre_analysis_post_content', 'mw_add_custom_to_yoast');
	function mw_add_custom_to_yoast($content) {
		global $post;
		$pid = $post->ID;
		$custom_content = '';
		$custom = get_post_custom($pid);
		unset($custom['_yoast_wpseo_focuskw']); // Don't count the keyword in the Yoast field!
		foreach($custom as $key => $value) {
			if (substr($key, 0, 1) != '_' && substr($value[0], -1) != '}' && !is_array($value[0]) && !empty($value[0])) {
				$custom_content.= $value[0] . ' ';
				}
			}

		$content = $content . ' ' . $custom_content;
		return $content;
		remove_filter('wpseo_pre_analysis_post_content', 'mw_add_custom_to_yoast'); // don't let WP execute this twice
		}
	}

// Extend WordPress search to include custom fields
// http://adambalee.com

function mw_search_join($join) {
	global $wpdb;
	if (is_search())
		{
		$join.= ' LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
		}

	return $join;
	}

add_filter('posts_join', 'mw_search_join');

function mw_search_where($where) {
	global $pagenow, $wpdb;
	if (is_search()) {
		$where = preg_replace("/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/", "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where);
		}

	return $where;
	}

add_filter('posts_where', 'mw_search_where');

function mw_search_distinct($where) {
	global $wpdb;
	if (is_search()) {
		return "DISTINCT";
		}

	return $where;
	}

add_filter('posts_distinct', 'mw_search_distinct');

// Reorder WordPress admin so that Pages is above Posts
add_action('admin_menu', 'mw_change_post_links');

function mw_change_post_links() {
    global $menu;
    $menu[6] = $menu[5];
    $menu[5] = $menu[20];
    unset($menu[20]);
}

// Modify the excerpt
// function wpse_allowedtags() {
	// Add custom tags to this string
// 		return '<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>';
// 	}
// if ( ! function_exists( 'wpse_custom_wp_trim_excerpt' ) ) :
// 	function wpse_custom_wp_trim_excerpt($wpse_excerpt) {
// 	$raw_excerpt = $wpse_excerpt;
// 		if ( '' == $wpse_excerpt ) {
// 			$wpse_excerpt = get_the_content('');
// 			$wpse_excerpt = strip_shortcodes( $wpse_excerpt );
// 			$wpse_excerpt = apply_filters('the_content', $wpse_excerpt);
// 			$wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
// 			$wpse_excerpt = strip_tags($wpse_excerpt, wpse_allowedtags()); /*IF you need to allow just certain tags. Delete if all tags are allowed */
// 			//Set the excerpt word count and only break after sentence is complete.
// 				$excerpt_word_count = 80;
// 				$excerpt_length = apply_filters('excerpt_length', $excerpt_word_count);
// 				$tokens = array();
// 				$excerptOutput = '';
// 				$count = 0;
// 				// Divide the string into tokens; HTML tags, or words, followed by any whitespace
// 				preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);
// 				foreach ($tokens[0] as $token) {
// 					if ($count >= $excerpt_length && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) {
// 					// Limit reached, continue until , ; ? . or ! occur at the end
// 						$excerptOutput .= trim($token);
// 						break;
// 					}
// 					// Add words to complete sentence
// 					$count++;
// 					// Append what's left of the token
// 					$excerptOutput .= $token;
// 				}
// 			$wpse_excerpt = trim(force_balance_tags($excerptOutput));
// 				$excerpt_end = ' <div class="more-link"><a class="read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Read More') . '</a></div>';
// 				$excerpt_more = apply_filters('excerpt_more', '' . $excerpt_end);
// 				// After the content
// 				$wpse_excerpt .= $excerpt_more; /*Add read more in new paragraph */
// 			return $wpse_excerpt;
// 		}
// 		return apply_filters('wpse_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
// 	}
// endif;
// remove_filter('get_the_excerpt', 'wp_trim_excerpt');
// add_filter('get_the_excerpt', 'wpse_custom_wp_trim_excerpt');


// Custom excerpt lengths
// function excerpt($limit) {
// 	  $excerpt = explode(' ', get_the_excerpt(), $limit);
// 	  if (count($excerpt)>=$limit) {
// 		array_pop($excerpt);
// 		$excerpt = implode(" ",$excerpt).'...';
// 	  } else {
// 		$excerpt = implode(" ",$excerpt);
// 	  }
// 	  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
// 	  return $excerpt;
// 	}
//
// 	function content($limit) {
// 	  $content = explode(' ', get_the_content(), $limit);
// 	  if (count($content)>=$limit) {
// 		array_pop($content);
// 		$content = implode(" ",$content).'...';
// 	  } else {
// 		$content = implode(" ",$content);
// 	  }
// 	  $content = preg_replace('/\[.+\]/','', $content);
// 	  $content = apply_filters('the_content', $content);
// 	  $content = str_replace(']]>', ']]&gt;', $content);
// 	  return $content;
// }

?>
