<?php
/**
 * The template for displaying the Project archive
 *
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

$templates = ['archive-project.twig', 'index.twig'];

global $paged;
if (!isset($paged) || !$paged) {
  $paged = 1;
}

$context = Timber::get_context();

$args = [
  'post_type' => 'project',
  'paged' => $paged,
];

$context['posts'] = Timber::get_posts($args);

$context['pagination'] = Timber::get_pagination();

Timber::render($templates, $context);
