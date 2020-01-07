<?php
/**
 * Template Name: Sign Up
 * The template for displaying the Newsletter Sign Up page.
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
Timber::render( 'page-sign-up.twig', $data );
