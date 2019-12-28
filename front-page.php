<?php
/**
 * Template Name: Front Page
 * The template for displaying the front page.
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
Timber::render( 'front-page.twig', $data );
