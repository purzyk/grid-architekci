<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );
} );

add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style(
		'grid-chrome',
		get_theme_file_uri( 'assets/css/chrome.css' ),
		array( 'global-styles' ),
		filemtime( get_theme_file_path( 'assets/css/chrome.css' ) )
	);
} );
