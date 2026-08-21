<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );
	// So Tailwind-classed blocks preview correctly (WYSIWYG) in the editor iframe.
	add_editor_style( 'assets/css/tailwind-build.css' );
} );

add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style(
		'grid-tailwind',
		get_theme_file_uri( 'assets/css/tailwind-build.css' ),
		array( 'global-styles' ),
		filemtime( get_theme_file_path( 'assets/css/tailwind-build.css' ) )
	);

	// Printed in <head> (default $in_footer = false), not deferred: it has
	// to set .dark on <html> before the browser paints the body, or the
	// wrong theme flashes on load.
	wp_enqueue_script(
		'grid-theme-toggle',
		get_theme_file_uri( 'assets/js/theme-toggle.js' ),
		array(),
		filemtime( get_theme_file_path( 'assets/js/theme-toggle.js' ) )
	);
} );
