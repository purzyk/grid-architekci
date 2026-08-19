<?php
/**
 * Populates the real privacy policy / cookies / regulamin content, and sets
 * the page's slug to match the old site's URL exactly
 * (polityka-prywatnosci-i-plikow-cookies-oraz-regulamin) so it's a direct
 * continuation — no redirect needed, same pattern already used for every
 * projekt slug.
 * Run via: ddev wp eval-file wordpress/wp-content/seed-content/seed-privacy-policy.php
 */

$content = file_get_contents( __DIR__ . '/privacy-policy-content.html' );

$existing = get_page_by_path( 'privacy-policy' ) ?: get_page_by_path( 'polityka-prywatnosci-i-plikow-cookies-oraz-regulamin' );

$post_data = array(
	'post_title'   => 'Polityka prywatności i plików cookies oraz Regulamin',
	'post_name'    => 'polityka-prywatnosci-i-plikow-cookies-oraz-regulamin',
	'post_content' => $content,
	'post_status'  => 'publish',
	'post_type'    => 'page',
);

if ( $existing ) {
	$post_data['ID'] = $existing->ID;
	$id = wp_update_post( $post_data, true );
} else {
	$id = wp_insert_post( $post_data, true );
}

if ( is_wp_error( $id ) ) {
	echo 'Failed: ' . $id->get_error_message() . "\n";
} else {
	echo "Privacy policy page published: post #{$id}\n";
}
