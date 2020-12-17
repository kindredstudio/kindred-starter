<?php
// Filter allowed block types by template
add_filter('allowed_block_types', 'kindred_allowed_block_types', 10, 2);

function kindred_allowed_block_types($allowed_blocks, $post)
{
  $allowed_blocks = [
    'acf/text-and-image',
    'acf/hero',
    'acf/banner',
    'acf/accordion',
    'acf/contact-form',
    'core/shortcode',
    // 'core/paragraph',
    // 'core/heading',
    // 'core/image',
    // 'core/list',
    // 'core/quote',
    // 'core/audio',
    // 'core/cover',
    // 'core/file',
    // 'core/video',
    // 'core/table',
    // 'core/verse',
    // 'core/code',
    // 'core/freeform',
    // 'core/html',
    // 'core/preformatted',
    // 'core/pullquote',
    // 'core/button',
    // 'core/text-columns',
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
  ];

  // if( $post->post_type === 'page' ) {
  // 	$allowed_blocks[] = 'core/shortcode';
  // }

  // if( get_page_template_slug($post->ID) === 'page-contact.php' ) {
  // 	$allowed_blocks[] = 'core-embed/wordpress-tv';
  // }

  return $allowed_blocks;
}
