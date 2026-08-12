<?php
/**
 * Plugin Name: GRID Blocks
 * Description: Custom block library for the GRID Architekci site. Kept separate from grid-core (which owns the CPT/taxonomy/ACF data model) so blocks and content survive independently of each other and of a future theme change.
 * Version: 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'block_categories_all', function( $categories ) {
	return array_merge(
		array( array( 'slug' => 'grid-blocks', 'title' => 'GRID' ) ),
		$categories
	);
} );

add_action( 'init', function() {
	$build_dir = __DIR__ . '/build';
	$manifest  = $build_dir . '/blocks-manifest.php';

	if ( ! file_exists( $manifest ) ) {
		return; // npm run build hasn't been run yet
	}

	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
		wp_register_block_types_from_metadata_collection( $build_dir, $manifest );
		return;
	}

	// Fallback for WP < 6.7.
	foreach ( array_keys( require $manifest ) as $block_dir ) {
		register_block_type( $build_dir . '/' . $block_dir );
	}
} );
