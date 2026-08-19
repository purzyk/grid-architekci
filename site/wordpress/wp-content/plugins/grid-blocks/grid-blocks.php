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

/**
 * Restrict the inserter on Pages to the blocks the design system actually
 * styles. Every marketing page (front page, O nas, Osiągnięcia, Kontakt) is
 * built from these — anything else in core (cover, table, media-text,
 * embeds, social icons...) renders with zero Tailwind styling and would
 * clash with the mock-matched design. Existing content isn't affected —
 * this only trims what the inserter offers, so already-placed blocks like
 * the raw wp:html backgrounds still render and remain editable in place.
 */
add_filter( 'allowed_block_types_all', function( $allowed_blocks, $editor_context ) {
	if ( empty( $editor_context->post ) || 'page' !== $editor_context->post->post_type ) {
		return $allowed_blocks;
	}

	static $grid_blocks = null;
	if ( null === $grid_blocks ) {
		$grid_blocks = array();
		foreach ( glob( __DIR__ . '/build/*/block.json' ) as $block_json ) {
			$data = json_decode( file_get_contents( $block_json ), true );
			if ( ! empty( $data['name'] ) ) {
				$grid_blocks[] = $data['name'];
			}
		}
	}

	$core_blocks = array(
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/list-item',
		'core/image',
		'core/group',
		'core/html',
	);

	return array_merge( $core_blocks, $grid_blocks );
}, 10, 2 );
