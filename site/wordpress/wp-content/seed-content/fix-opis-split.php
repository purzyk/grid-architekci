<?php
/**
 * Re-derive lead/opis for all 48 projects from the raw legacy description
 * text, replacing the first pass (which esc_html()'d whatever HTML the
 * source already had embedded — turning a handful of stray <table>/<a>/
 * <strong> tags into visible literal "&lt;table&gt;" garbage — and split
 * the two-column layout purely on whether the source happened to contain
 * a double-newline, which most projects' descriptions never had).
 *
 * New rule: strip any embedded HTML first, then split into two balanced
 * paragraphs at the sentence boundary nearest the midpoint — but only
 * when the text is long enough that two columns actually look better
 * than one (under ~400 characters, a two-line paragraph split into two
 * columns looks sparse and disjointed, so leave those as a single column).
 *
 * Run via: ddev wp eval-file wordpress/wp-content/seed-content/fix-opis-split.php
 */

function grid_opis_clean_text( $raw ) {
	$text = wp_strip_all_tags( $raw, true );
	$text = trim( preg_replace( '/\s+/u', ' ', $text ) );
	return $text;
}

function grid_opis_sentences( $text ) {
	$parts = preg_split( '/(?<=[.!?])\s+(?=[A-ZŁŚŻŹĆŃÓĄĘ0-9])/u', $text );
	return array_values( array_filter( array_map( 'trim', $parts ) ) );
}

function grid_opis_first_sentence( $text, $max_len = 220 ) {
	$sentences = grid_opis_sentences( $text );
	$sentence  = $sentences[0] ?? $text;
	if ( mb_strlen( $sentence ) > $max_len ) {
		$sentence = mb_substr( $sentence, 0, $max_len );
		$sentence = preg_replace( '/\s+\S*$/u', '', $sentence ) . '…';
	}
	return $sentence;
}

function grid_opis_split( $text, $min_len_to_split = 400 ) {
	if ( mb_strlen( $text ) < $min_len_to_split ) {
		return array( $text, '' );
	}
	$sentences = grid_opis_sentences( $text );
	if ( count( $sentences ) < 3 ) {
		return array( $text, '' );
	}
	$total = mb_strlen( $text );
	$half  = $total / 2;
	$cum   = 0;
	$best_idx  = 0;
	$best_diff = PHP_INT_MAX;
	foreach ( $sentences as $i => $s ) {
		$cum += mb_strlen( $s ) + 1;
		$diff = abs( $cum - $half );
		if ( $diff < $best_diff ) {
			$best_diff = $diff;
			$best_idx  = $i;
		}
	}
	$best_idx = max( 0, min( $best_idx, count( $sentences ) - 2 ) );
	$left     = implode( ' ', array_slice( $sentences, 0, $best_idx + 1 ) );
	$right    = implode( ' ', array_slice( $sentences, $best_idx + 1 ) );
	return array( $left, $right );
}

$full_json = json_decode( file_get_contents( __DIR__ . '/legacy-projects-full.json' ), true );
$meta_json = json_decode( file_get_contents( __DIR__ . '/legacy-projects-meta.json' ), true )['projects'];

$meta_by_id = array();
foreach ( $meta_json as $m ) {
	$meta_by_id[ $m['old_id'] ] = $m;
}

$updated = 0;
foreach ( $full_json as $proj ) {
	if ( ! $proj['description'] ) {
		continue;
	}
	$meta = $meta_by_id[ $proj['old_id'] ] ?? null;
	if ( ! $meta ) {
		continue;
	}
	$slug = ( 1745 === $proj['old_id'] ) ? 'almark-leszno-2' : $meta['slug'];
	$post = get_page_by_path( $slug, OBJECT, 'projekt' );
	if ( ! $post ) {
		continue;
	}

	$clean = grid_opis_clean_text( $proj['description'] );
	$lead  = grid_opis_first_sentence( $clean );
	list( $left, $right ) = grid_opis_split( $clean );

	$opis_html = '<p>' . esc_html( $left ) . '</p>';
	if ( $right ) {
		$opis_html .= "\n" . '<p>' . esc_html( $right ) . '</p>';
	}

	update_field( 'field_projekt_lead', $lead, $post->ID );
	update_field( 'field_projekt_opis', $opis_html, $post->ID );

	$updated++;
	echo "{$meta['title']}: " . ( $right ? 'split into 2 paragraphs' : 'kept as 1 paragraph' ) . " (" . mb_strlen( $clean ) . " chars)\n";
}

echo "\nUpdated {$updated} projects.\n";
