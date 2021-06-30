<?php
/**
 * The template for displaying the front page
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render(['front-page.twig'], $context);
