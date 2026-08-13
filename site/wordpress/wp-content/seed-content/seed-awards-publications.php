<?php
/**
 * One-off seed script for the nagroda/publikacja CPTs — this content was
 * never migrated when the block library was built, leaving Osiągnięcia
 * empty. Run via: ddev wp eval-file wordpress/wp-content/seed-content/seed-awards-publications.php
 * Source: projekt/grid-o-nas.dc.html's AWARDS/pubs.items arrays.
 */

if ( ! function_exists( 'update_field' ) ) {
	echo "ACF not active — aborting.\n";
	return;
}

// Wipe any previous run so this script is safely re-runnable.
foreach ( array( 'nagroda', 'publikacja' ) as $pt ) {
	$existing = get_posts( array( 'post_type' => $pt, 'posts_per_page' => -1, 'post_status' => 'any', 'fields' => 'ids' ) );
	foreach ( $existing as $id ) {
		wp_delete_post( $id, true );
	}
}

$awards = array(
	array( 'rok' => 2023, 'konkurs' => 'Piękny Wrocław, XXXII edycja', 'wynik' => 'I nagroda' ),
	array( 'rok' => 2023, 'konkurs' => 'Zabytek Zadbany', 'wynik' => 'Laureat', 'featured' => true, 'opis' => 'Stara Szwalnia w Lesznie — adaptacja fabryki na mieszkania.' ),
	array( 'rok' => 2021, 'konkurs' => 'Polski Cement w architekturze', 'wynik' => 'Nominacja' ),
	array( 'rok' => 2021, 'konkurs' => 'IDOL Zachodniopomorskie', 'wynik' => 'Laureat' ),
	array( 'rok' => 2020, 'konkurs' => 'Konkurs SARP', 'wynik' => 'Wyróżnienie' ),
	array( 'rok' => 2019, 'konkurs' => 'Piękny Wrocław, XXIX edycja', 'wynik' => 'Wyróżnienie' ),
	array( 'rok' => 2017, 'konkurs' => 'Najlepsza Przestrzeń Publiczna Woj. Śląskiego', 'wynik' => 'Nagroda' ),
	array( 'rok' => 2017, 'konkurs' => 'Centrum Aktywności Lokalnej, Szczecin', 'wynik' => 'I nagroda' ),
	array( 'rok' => 2016, 'konkurs' => 'Karkonoskie Spotkania Architektów KASA', 'wynik' => 'III miejsce' ),
	array( 'rok' => 2016, 'konkurs' => 'Konkurs Fundacji Familijny', 'wynik' => 'Wyróżnienie' ),
	array( 'rok' => 2016, 'konkurs' => 'Konkurs UG Drezdenko', 'wynik' => 'Wyróżnienie' ),
	array( 'rok' => 2014, 'konkurs' => 'Dworzec, Piekary Śląskie', 'wynik' => 'I nagroda', 'featured' => true, 'opis' => 'Zrealizowany; Najlepsza Przestrzeń Publiczna Śląska 2017.' ),
	array( 'rok' => 2014, 'konkurs' => 'Piękny Wrocław, XXIV edycja', 'wynik' => 'II nagroda' ),
	array( 'rok' => 2013, 'konkurs' => 'Piękny Wrocław, XXIII edycja', 'wynik' => 'I nagroda' ),
	array( 'rok' => 2013, 'konkurs' => 'Piękny Wrocław, XXIII edycja', 'wynik' => 'II nagroda' ),
	array( 'rok' => 2012, 'konkurs' => 'Profesjonaliści Forbesa', 'wynik' => 'Laureat' ),
	array( 'rok' => 2010, 'konkurs' => 'Konkurs SARP', 'wynik' => 'III nagroda' ),
	array( 'rok' => 2008, 'konkurs' => 'Konkurs SARP', 'wynik' => 'II nagroda' ),
	array( 'rok' => 2008, 'konkurs' => 'Konkurs UM Zawiercie', 'wynik' => 'III nagroda' ),
	array( 'rok' => 2008, 'konkurs' => 'Konkurs SARP', 'wynik' => 'Wyróżnienie' ),
	array( 'rok' => 2003, 'konkurs' => 'Europan 7, Kristianstad', 'wynik' => 'I nagroda', 'featured' => true, 'opis' => 'Pierwszy polski zespół z I nagrodą w Europan.' ),
);

foreach ( $awards as $a ) {
	$id = wp_insert_post( array(
		'post_type'   => 'nagroda',
		'post_status' => 'publish',
		'post_title'  => $a['konkurs'] . ' (' . $a['rok'] . ')',
	) );
	update_field( 'field_nagroda_rok', $a['rok'], $id );
	update_field( 'field_nagroda_konkurs', $a['konkurs'], $id );
	update_field( 'field_nagroda_wynik', $a['wynik'], $id );
	update_field( 'field_nagroda_wyrozniona', ! empty( $a['featured'] ), $id );
	if ( ! empty( $a['opis'] ) ) {
		update_field( 'field_nagroda_opis', $a['opis'], $id );
	}
}
echo 'Created ' . count( $awards ) . " nagroda posts.\n";

$pubs = array(
	array( 'title' => 'Architektura Murator 06/2021', 'typ' => 'Prasa branżowa', 'rok' => 2021, 'img' => 'architektura-06-2021.jpeg' ),
	array( 'title' => 'Architektura Dolnego Śląska', 'typ' => 'Katalog wystawy', 'rok' => 2017, 'img' => 'architektura-dolnego-slaska.jpg' ),
	array( 'title' => 'Murator 05/2016', 'typ' => 'Prasa branżowa', 'rok' => 2016, 'img' => 'murator-05-2016.jpg' ),
	array( 'title' => 'Murator 03/2014', 'typ' => 'Prasa branżowa', 'rok' => 2014, 'img' => 'murator-03-2014.jpg' ),
	array( 'title' => 'Ładny Dom 11/2013', 'typ' => 'Prasa branżowa', 'rok' => 2013, 'img' => 'ladnydom-11-2013.jpg' ),
	array( 'title' => 'Architekturführer Stettin / Szczecin', 'typ' => 'Książka', 'rok' => 2019, 'img' => 'przewodnik-po-szczecinie.jpg' ),
	array( 'title' => 'Europan 7 — European Results', 'typ' => 'Katalog konkursowy', 'rok' => 2003, 'img' => 'europan-7-european-results.jpg' ),
	array( 'title' => 'Architektura Murator 04/2004', 'typ' => 'Prasa branżowa', 'rok' => 2004, 'img' => 'architektura-04-2004.jpg' ),
);

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

foreach ( $pubs as $p ) {
	$id = wp_insert_post( array(
		'post_type'   => 'publikacja',
		'post_status' => 'publish',
		'post_title'  => $p['title'],
	) );
	update_field( 'field_publikacja_typ', $p['typ'], $id );
	update_field( 'field_publikacja_rok', $p['rok'], $id );

	$path = ABSPATH . 'wp-content/_migration-staging/publikacje/' . $p['img'];
	if ( file_exists( $path ) ) {
		$tmp = wp_tempnam( $p['img'] );
		copy( $path, $tmp );
		$file_array = array( 'name' => $p['img'], 'tmp_name' => $tmp );
		$attach_id  = media_handle_sideload( $file_array, $id );
		if ( ! is_wp_error( $attach_id ) ) {
			set_post_thumbnail( $id, $attach_id );
		} else {
			echo 'Image sideload failed for ' . $p['title'] . ': ' . $attach_id->get_error_message() . "\n";
		}
	} else {
		echo 'Missing source image: ' . $path . "\n";
	}
}
echo 'Created ' . count( $pubs ) . " publikacja posts.\n";
