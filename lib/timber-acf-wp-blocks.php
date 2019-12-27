<?php
// https://github.com/palmiak/timber-acf-wp-blocks
/**
 * Check whether WordPress and ACF are available; bail if not.
 **/

if ( ! function_exists( 'acf_register_block' ) ) {
	return;
}
if ( ! function_exists( 'add_filter' ) ) {
	return;
}
if ( ! function_exists( 'add_action' ) ) {
	return;
}

/**
 * Create blocks based on templates found in Timber's "views/blocks" directory
 */
add_action(
	'acf/init',
	function () {
		// Get an array of directories containing blocks.
		$directories = apply_filters( 'timber/acf-gutenberg-blocks-templates', ['views/blocks'] );

		// Check whether ACF exists before continuing.
		foreach ( $directories as $dir ) {
			// Sanity check whether the directory we're iterating over exists first.
			if ( ! file_exists( \locate_template( $dir ) ) ) {
				return;
			}
			// Iterate over the directories provided and look for templates.
			$template_directory = new \DirectoryIterator( \locate_template( $dir ) );
			foreach ( $template_directory as $template ) {

				if ( ! $template->isDot() && ! $template->isDir() ) {
					// Strip the file extension to get the slug.
					$slug = str_replace( '.twig', '', $template->getFilename() );

					// Get header info from the found template file(s).
					$file_path    = locate_template( $dir . "/${slug}.twig" );
					$file_headers = get_file_data(
						$file_path,
						[
							'title'             => 'Title',
							'description'       => 'Description',
							'category'          => 'Category',
							'icon'              => 'Icon',
							'keywords'          => 'Keywords',
							'mode'              => 'Mode',
							'align'             => 'Align',
							'post_types'        => 'PostTypes',
							'supports_align'    => 'SupportsAlign',
							'supports_mode'     => 'SupportsMode',
							'supports_multiple' => 'SupportsMultiple',
							'enqueue_style'     => 'EnqueueStyle',
							'enqueue_script'    => 'EnqueueScript',
							'enqueue_assets'    => 'EnqueueAssets',
						]
					);

					if ( empty( $file_headers['title'] ) ) {
						continue;
					}
					if ( empty( $file_headers['category'] ) ) {
						continue;
					}
					// Set up block data for registration.
					$context = [
						'name'            => $slug,
						'title'           => $file_headers['title'],
						'description'     => $file_headers['description'],
						'category'        => $file_headers['category'],
						'icon'            => $file_headers['icon'],
						'keywords'        => explode( ' ', $file_headers['keywords'] ),
						'mode'            => $file_headers['mode'],
						'render_callback' => 'timber_blocks_callback',
						'enqueue_style'   => $file_headers['enqueue_style'],
						'enqueue_script'  => $file_headers['enqueue_script'],
						'enqueue_assets'  => $file_headers['enqueue_assets'],
					];
					// If the PostTypes header is set in the template, restrict this block to those types.
					if ( ! empty( $file_headers['post_types'] ) ) {
						$context['post_types'] = explode( ' ', $file_headers['post_types'] );
					}
					// If the SupportsAlign header is set in the template, restrict this block to those aligns.
					if ( ! empty( $file_headers['supports_align'] ) ) {
						$context['supports']['align'] = in_array( $file_headers['supports_align'], [ 'true', 'false' ], true ) ? filter_var( $file_headers['supports_align'], FILTER_VALIDATE_BOOLEAN ) : explode( ' ', $file_headers['supports_align'] );
					}
					// If the SupportsMode header is set in the template, restrict this block mode feature.
					if ( ! empty( $file_headers['supports_mode'] ) ) {
						$context['supports']['mode'] = 'true' === $file_headers['supports_mode'] ? true : false;
					}
					// If the SupportsMultiple header is set in the template, restrict this block multiple feature.
					if ( ! empty( $file_headers['supports_multiple'] ) ) {
						$context['supports']['multiple'] = 'true' === $file_headers['supports_multiple'] ? true : false;
					}

					// Register the block with ACF.
					acf_register_block_type( $context );
				}
			}
		}
	}
);

/**
 * Callback to register blocks
 */
function timber_blocks_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	// Set up the slug to be useful.
	$context = Timber::get_context();
	$slug    = str_replace( 'acf/', '', $block['name'] );

	$context['block']      = $block;
	$context['post_id']    = $post_id;
	$context['slug']       = $slug;
	$context['is_preview'] = $is_preview;
	$context['fields']     = get_fields();

	$paths = timber_acf_path_render( $slug );

	Timber::render( $paths, $context );
}

/**
 * Generates array with paths and slugs
 */
function timber_acf_path_render( $slug ) {
	$directories = apply_filters( 'timber/acf-gutenberg-blocks-templates', ['views/blocks'] );

	foreach( $directories as $directory ) {
		$ret[] = $directory . "/{$slug}.twig";
	}

	return $ret;
}