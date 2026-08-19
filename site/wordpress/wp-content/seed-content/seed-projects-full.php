<?php
/**
 * Comprehensive re-import of real project content, sourced from the
 * legacy site's Bridge-theme meta boxes (see legacy-projects-full.json,
 * extracted via grid-legacy/export-full.php) joined with the already
 * -reviewed category/status/year mapping in legacy-projects-meta.json
 * (extracted via grid-legacy/export-projects.php).
 *
 * Updates the 48 existing `projekt` posts in place (matched by slug —
 * they already exist with correct title/kategoria/status from an earlier
 * seed pass, just without gallery/description/info). Does not create or
 * delete posts.
 *
 * Run via: ddev wp eval-file wordpress/wp-content/seed-content/seed-projects-full.php
 */

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

function grid_full_clean_label( $label ) {
	$label = str_replace( "\xC2\xA0", ' ', $label ); // non-breaking space -> space
	$label = trim( $label );
	$label = rtrim( $label, ": \t" );
	return trim( $label );
}

function grid_full_first_sentence( $text, $max_len = 220 ) {
	$text = trim( $text );
	if ( preg_match( '/^(.{20,}?[.!?])\s+[A-ZŁŚŻŹĆŃÓĄĘ]/u', $text, $m ) ) {
		$sentence = $m[1];
	} else {
		$sentence = $text;
	}
	if ( mb_strlen( $sentence ) > $max_len ) {
		$sentence = mb_substr( $sentence, 0, $max_len );
		$sentence = preg_replace( '/\s+\S*$/u', '', $sentence ) . '…';
	}
	return $sentence;
}

function grid_full_sideload( $abs_path, $filename, $post_id ) {
	if ( ! file_exists( $abs_path ) ) {
		return null;
	}
	$tmp = wp_tempnam( $filename );
	copy( $abs_path, $tmp );
	$file_array = array( 'name' => $filename, 'tmp_name' => $tmp );
	$attach_id  = media_handle_sideload( $file_array, $post_id );
	if ( is_wp_error( $attach_id ) ) {
		echo "  Sideload failed for {$filename}: " . $attach_id->get_error_message() . "\n";
		return null;
	}
	return $attach_id;
}

$full_json = json_decode( file_get_contents( __DIR__ . '/legacy-projects-full.json' ), true );
$meta_json = json_decode( file_get_contents( __DIR__ . '/legacy-projects-meta.json' ), true )['projects'];

$meta_by_id = array();
foreach ( $meta_json as $m ) {
	$meta_by_id[ $m['old_id'] ] = $m;
}

// Old site's flat komercyjne/do-mieszkania codes -> our real term names
// (registered with Polish diacritics, e.g. "przemysłowe" not "przemyslowe").
$kategoria_name_map = array(
	'mieszkalne'  => 'mieszkalne',
	'publiczne'   => 'publiczne',
	'przemyslowe' => 'przemysłowe',
);

$staging_root = ABSPATH . 'wp-content/_migration-staging/projekty/';

$updated = 0;
$skipped = array();

foreach ( $full_json as $proj ) {
	$meta = $meta_by_id[ $proj['old_id'] ] ?? null;
	if ( ! $meta ) {
		$skipped[] = $proj['title'] . ' (no meta entry)';
		continue;
	}

	// Two legacy "Almark, Leszno" projects share the same source slug
	// (old_id 1617 and 1745) — WordPress auto-deduped the second one to
	// "almark-leszno-2" when the new site's posts were first created, so
	// look that up explicitly instead of colliding both onto the first.
	$slug = ( 1745 === $proj['old_id'] ) ? 'almark-leszno-2' : $meta['slug'];

	$post = get_page_by_path( $slug, OBJECT, 'projekt' );
	if ( ! $post ) {
		$skipped[] = $proj['title'] . ' (no matching projekt post for slug ' . $slug . ')';
		continue;
	}
	$post_id = $post->ID;

	// Split the info list: pull a real "Rok:" value out to override the
	// post_date-derived year from export.json when present, drop "Status:"
	// entirely since the structured taxonomy term already covers it, keep
	// everything else as metryka repeater rows.
	$rok        = null;
	$info_clean = array();
	foreach ( $proj['info'] as $row ) {
		$label       = grid_full_clean_label( $row['label'] );
		$label_lower = mb_strtolower( $label );
		if ( 'rok' === $label_lower && preg_match( '/(19|20)\d{2}/', $row['value'], $m ) ) {
			$rok = (int) $m[0];
			continue;
		}
		if ( 'status' === $label_lower ) {
			continue;
		}
		$info_clean[] = array( 'label' => $label, 'value' => trim( $row['value'] ) );
	}
	if ( ! $rok ) {
		$rok = $meta['year'];
	}
	update_field( 'field_projekt_rok', $rok, $post_id );

	if ( $proj['description'] ) {
		update_field( 'field_projekt_lead', grid_full_first_sentence( $proj['description'] ), $post_id );
		update_field( 'field_projekt_opis', '<p>' . esc_html( $proj['description'] ) . '</p>', $post_id );
	}

	if ( ! empty( $kategoria_name_map[ $meta['kategoria'] ] ) ) {
		wp_set_object_terms( $post_id, array( $kategoria_name_map[ $meta['kategoria'] ] ), 'projekt_kategoria' );
	}
	if ( ! empty( $meta['status'] ) ) {
		wp_set_object_terms( $post_id, array( $meta['status'] ), 'projekt_status' );
	}

	// Gallery images, splitting out any site-plan/zagospodarowanie drawing.
	$gallery_ids_new = array();
	$rysunek_id_new  = null;
	foreach ( $proj['gallery_ids'] as $old_img_id ) {
		$rel = $proj['files'][ $old_img_id ] ?? null;
		if ( ! $rel ) {
			continue;
		}
		$filename = basename( $rel );
		$new_id   = grid_full_sideload( $staging_root . $rel, $filename, $post_id );
		if ( ! $new_id ) {
			continue;
		}
		if ( ! $rysunek_id_new && preg_match( '/pzt|zagospodarowani/i', $filename ) ) {
			$rysunek_id_new = $new_id;
		} else {
			$gallery_ids_new[] = $new_id;
		}
	}
	if ( $gallery_ids_new ) {
		update_field( 'field_projekt_galeria', $gallery_ids_new, $post_id );
	}
	if ( $rysunek_id_new ) {
		update_field( 'field_projekt_rysunek', $rysunek_id_new, $post_id );
	}

	// Featured/hero image — sideloaded separately even if it duplicates a
	// gallery photo (matches the same pattern already used for Zapolice).
	if ( $proj['thumbnail_id'] && ! empty( $proj['files'][ $proj['thumbnail_id'] ] ) ) {
		$rel      = $proj['files'][ $proj['thumbnail_id'] ];
		$filename = basename( $rel );
		$thumb_id = grid_full_sideload( $staging_root . $rel, $filename, $post_id );
		if ( $thumb_id ) {
			set_post_thumbnail( $post_id, $thumb_id );
		}
	}

	if ( $info_clean ) {
		update_field( 'field_projekt_metryka', $info_clean, $post_id );
	}

	$updated++;
	echo "Updated: {$meta['title']} (post #{$post_id}, " . count( $gallery_ids_new ) . " gallery images)\n";
}

echo "\nDone. Updated {$updated} projects.\n";
if ( $skipped ) {
	echo 'Skipped: ' . count( $skipped ) . "\n";
	foreach ( $skipped as $s ) {
		echo "  - {$s}\n";
	}
}
