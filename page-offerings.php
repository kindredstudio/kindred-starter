<?php
/**
 * Template Name: Offerings
 * The template for displaying the Offerings page.
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$data = Timber::get_context();
$post = new TimberPost();
$data['post'] = $post;
Timber::render( 'page-offerings.twig', $data );
