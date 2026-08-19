<?php
/**
 * Plugin Name: GRID Core
 * Description: Content model for the GRID Architekci site — projekt/zespol/nagroda/publikacja post types and the projekt taxonomies. Kept in a plugin, not the theme, so the data model survives a theme change.
 * Version: 0.1.0
 * Update URI: false
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/acf-fields.php';

add_action( 'init', 'grid_core_register_post_types' );
function grid_core_register_post_types() {

	register_post_type( 'projekt', array(
		'labels'       => array(
			'name'          => 'Projekty',
			'singular_name' => 'Projekt',
			'add_new_item'  => 'Dodaj projekt',
			'edit_item'     => 'Edytuj projekt',
			'all_items'     => 'Wszystkie projekty',
		),
		'public'       => true,
		'has_archive'  => false, // the front page grid covers this; singles still need their own permalinks
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-building',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite'      => array( 'slug' => 'projekt' ),
	) );

	register_post_type( 'zespol', array(
		'labels'       => array(
			'name'          => 'Zespół',
			'singular_name' => 'Członek zespołu',
			'add_new_item'  => 'Dodaj osobę',
			'edit_item'     => 'Edytuj osobę',
			'all_items'     => 'Zespół',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-groups',
		// 'page-attributes' gives editors a plain "Order" field (block
		// editor sidebar + Quick Edit) to control display order on the
		// team grid — it's read via menu_order in team-grid/render.php.
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
	) );

	register_post_type( 'nagroda', array(
		'labels'       => array(
			'name'          => 'Nagrody',
			'singular_name' => 'Nagroda',
			'add_new_item'  => 'Dodaj nagrodę',
			'edit_item'     => 'Edytuj nagrodę',
			'all_items'     => 'Nagrody',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-awards',
		'supports'     => array( 'title' ),
	) );

	register_post_type( 'publikacja', array(
		'labels'       => array(
			'name'          => 'Publikacje',
			'singular_name' => 'Publikacja',
			'add_new_item'  => 'Dodaj publikację',
			'edit_item'     => 'Edytuj publikację',
			'all_items'     => 'Publikacje',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-media-document',
		'supports'     => array( 'title', 'thumbnail' ),
	) );
}

add_action( 'init', 'grid_core_register_taxonomies' );
function grid_core_register_taxonomies() {

	register_taxonomy( 'projekt_kategoria', 'projekt', array(
		'labels'       => array(
			'name'          => 'Kategorie',
			'singular_name' => 'Kategoria',
		),
		'hierarchical' => true,
		'public'       => true,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'kategoria' ),
	) );

	register_taxonomy( 'projekt_status', 'projekt', array(
		'labels'       => array(
			'name'          => 'Status',
			'singular_name' => 'Status',
		),
		'hierarchical' => true,
		'public'       => true,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'status' ),
	) );
}

/**
 * Default terms, matching projekt_kategoria/projekt_status split from
 * SPEC.md — the old site's flat `project_category` taxonomy conflated
 * both. Registered on activation so a fresh install/migration always has
 * these to work with.
 */
register_activation_hook( __FILE__, 'grid_core_activate' );
function grid_core_activate() {
	grid_core_register_post_types();
	grid_core_register_taxonomies();

	$kategorie = array( 'mieszkalne', 'publiczne', 'przemysłowe' );
	foreach ( $kategorie as $term ) {
		if ( ! term_exists( $term, 'projekt_kategoria' ) ) {
			wp_insert_term( $term, 'projekt_kategoria' );
		}
	}

	$statusy = array( 'zrealizowane', 'w realizacji', 'konkurs', 'koncepcja' );
	foreach ( $statusy as $term ) {
		if ( ! term_exists( $term, 'projekt_status' ) ) {
			wp_insert_term( $term, 'projekt_status' );
		}
	}

	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, function() {
	flush_rewrite_rules();
} );
