<?php
/**
 * One-off seed script: imports real gallery photos + site plan drawings
 * for the two projekt posts whose old-site content actually survived
 * migration (grid-legacy/export.json) — most projects only got `rok`,
 * these two also have a real gallery. Source images copied into
 * wp-content/_migration-staging/projekty/ from grid-legacy/wordpress/media/
 * before running this (see the two blocks below for exact source paths).
 * Run via: ddev wp eval-file wordpress/wp-content/seed-content/seed-project-galleries.php
 */

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

function grid_sideload_to_post( $filename, $post_id ) {
	$path = ABSPATH . 'wp-content/_migration-staging/projekty/' . $filename;
	if ( ! file_exists( $path ) ) {
		echo "Missing source image: {$path}\n";
		return null;
	}
	$tmp = wp_tempnam( $filename );
	copy( $path, $tmp );
	$file_array = array( 'name' => $filename, 'tmp_name' => $tmp );
	$attach_id  = media_handle_sideload( $file_array, $post_id );
	if ( is_wp_error( $attach_id ) ) {
		echo "Sideload failed for {$filename}: " . $attach_id->get_error_message() . "\n";
		return null;
	}
	return $attach_id;
}

$projects = array(
	'dom-kultury-zapolice' => array(
		'site_plan' => 'pzt-na-strone-www-1.jpg',
		'gallery'   => array(
			'dom-kultury_1.jpg',
			'dom-kultury_2.jpg',
			'dom-kultury_3.jpg',
			'dom-kultury_4.jpg',
			'dom-kultury_5.jpg',
			'dom-kultury_6.jpg',
			'dom-kultury_biblio1.jpg',
			'dom-kultury_biblio2.jpg',
			'dom-kultury_sala.jpg',
			'dom-kultury_hol.jpg',
		),
	),
	'dom-na-krzykach-wroclaw' => array(
		'site_plan' => 'pzt.jpg',
		'gallery'   => array(
			'j4.jpg',
			'j10.jpg',
			'j12.jpg',
			'j16-scaled.jpg',
			'j19.jpg',
			'j20.jpg',
			'j31.jpg',
		),
	),
);

foreach ( $projects as $slug => $data ) {
	$post = get_page_by_path( $slug, OBJECT, 'projekt' );
	if ( ! $post ) {
		echo "No projekt post found for slug {$slug}\n";
		continue;
	}
	$post_id = $post->ID;

	$gallery_ids = array();
	foreach ( $data['gallery'] as $filename ) {
		$id = grid_sideload_to_post( $filename, $post_id );
		if ( $id ) {
			$gallery_ids[] = $id;
		}
	}
	if ( $gallery_ids ) {
		update_field( 'field_projekt_galeria', $gallery_ids, $post_id );
	}

	if ( ! empty( $data['site_plan'] ) ) {
		$plan_id = grid_sideload_to_post( $data['site_plan'], $post_id );
		if ( $plan_id ) {
			update_field( 'field_projekt_rysunek', $plan_id, $post_id );
		}
	}

	echo "{$slug} (ID {$post_id}): " . count( $gallery_ids ) . " gallery images" .
		( ! empty( $plan_id ) ? ' + site plan' : '' ) . "\n";
}
