<?php
/**
 * Run via: ddev wp eval-file wordpress/_migration-staging/create-pages.php
 * Creates the 4 real pages (front page uses its own template automatically
 * once set as the site's front page; single-projekt.html is already
 * auto-applied via the WP template hierarchy for the projekt CPT).
 */

function grid_upsert_page( $title, $slug, $content_file ) {
	$content = file_get_contents( __DIR__ . '/' . $content_file );
	$existing = get_page_by_path( $slug );

	$post_data = array(
		'post_type'    => 'page',
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
		'post_status'  => 'publish',
	);

	if ( $existing ) {
		$post_data['ID'] = $existing->ID;
		$id = wp_update_post( $post_data, true );
	} else {
		$id = wp_insert_post( $post_data, true );
	}

	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( "Failed: {$title}: " . $id->get_error_message() );
		return null;
	}

	WP_CLI::log( "{$title}: post #{$id}" );
	return $id;
}

$front_id = grid_upsert_page( 'Strona główna', 'strona-glowna', 'front-page-content.html' );
grid_upsert_page( 'O nas', 'o-nas', 'o-nas-content.html' );
grid_upsert_page( 'Osiągnięcia', 'osiagniecia', 'osiagniecia-content.html' );
grid_upsert_page( 'Kontakt', 'kontakt', 'kontakt-content.html' );

if ( $front_id ) {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_id );
	WP_CLI::success( "Front page set to post #{$front_id}." );
}
