<?php
/**
 * Template Name: JsonAPI
 */
define( 'WP_USE_THEMES', false ); // Don't load theme support functionality
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

//get theme header
get_header();

//get jsonapi_page
do_action('jsonapi_init');

//theme footer
get_footer();
