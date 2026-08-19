<?php
/**
 * ACF field groups for the GRID content model, per the handoff README's
 * field lists. Registered as code (not DB-stored) so the schema lives in
 * version control alongside the post type/taxonomy registration.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'grid_core_register_acf_fields' );
function grid_core_register_acf_fields() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'    => 'group_projekt',
		'title'  => 'Projekt — szczegóły',
		'fields' => array(
			array(
				'key'   => 'field_projekt_rok',
				'label' => 'Rok',
				'name'  => 'rok',
				'type'  => 'number',
			),
			array(
				'key'   => 'field_projekt_lead',
				'label' => 'Lead',
				'name'  => 'lead',
				'type'  => 'textarea',
				'rows'  => 2,
			),
			array(
				'key'   => 'field_projekt_opis',
				'label' => 'Opis (dwa akapity)',
				'name'  => 'opis',
				'type'  => 'wysiwyg',
				'tabs'  => 'text',
				'toolbar' => 'basic',
			),
			array(
				'key'   => 'field_projekt_galeria',
				'label' => 'Galeria',
				'name'  => 'galeria',
				'type'  => 'gallery',
			),
			array(
				'key'   => 'field_projekt_rysunek',
				'label' => 'Rysunek zagospodarowania',
				'name'  => 'rysunek_zagospodarowania',
				'type'  => 'image',
				'return_format' => 'id',
			),
			array(
				'key'     => 'field_projekt_metryka',
				'label'   => 'Metryka',
				'name'    => 'metryka',
				'type'    => 'repeater',
				'layout'  => 'table',
				'button_label' => 'Dodaj pozycję',
				'instructions' => 'Dowolna lista etykieta/wartość — Klient, Zakres, Zespół, Konstrukcja, Wykonawca, Ep/Ek, Nagrody/Media, itd. Rok i Status wyświetlają się automatycznie z pól/kategorii powyżej — nie trzeba ich tu powtarzać.',
				'sub_fields' => array(
					array( 'key' => 'field_m_label', 'label' => 'Etykieta', 'name' => 'label', 'type' => 'text' ),
					array( 'key' => 'field_m_value', 'label' => 'Wartość', 'name' => 'value', 'type' => 'text' ),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'projekt',
				),
			),
		),
	) );

	acf_add_local_field_group( array(
		'key'    => 'group_zespol',
		'title'  => 'Zespół — szczegóły',
		'fields' => array(
			array(
				'key'   => 'field_zespol_stanowisko',
				'label' => 'Stanowisko',
				'name'  => 'stanowisko',
				'type'  => 'text',
				'instructions' => 'np. architekt IARP',
			),
			array(
				'key'   => 'field_zespol_funkcja',
				'label' => 'Funkcja',
				'name'  => 'funkcja',
				'type'  => 'text',
				'instructions' => 'np. Prezes Zarządu',
			),
			array(
				'key'   => 'field_zespol_bio',
				'label' => 'Bio',
				'name'  => 'bio',
				'type'  => 'textarea',
				'rows'  => 3,
			),
			array(
				'key'   => 'field_zespol_linkedin',
				'label' => 'LinkedIn',
				'name'  => 'linkedin',
				'type'  => 'url',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'zespol',
				),
			),
		),
	) );

	acf_add_local_field_group( array(
		'key'    => 'group_nagroda',
		'title'  => 'Nagroda — szczegóły',
		'fields' => array(
			array( 'key' => 'field_nagroda_rok', 'label' => 'Rok', 'name' => 'rok', 'type' => 'number' ),
			array( 'key' => 'field_nagroda_konkurs', 'label' => 'Konkurs', 'name' => 'konkurs', 'type' => 'text' ),
			array( 'key' => 'field_nagroda_wynik', 'label' => 'Wynik', 'name' => 'wynik', 'type' => 'text', 'instructions' => 'np. I nagroda' ),
			array( 'key' => 'field_nagroda_top', 'label' => 'Znacząca', 'name' => 'top', 'type' => 'true_false', 'instructions' => 'Koloruje wynik w tabeli na pomarańczowo. Niezależne od pól poniżej.' ),
			array( 'key' => 'field_nagroda_wyrozniona', 'label' => 'Wyróżniona', 'name' => 'wyrozniona', 'type' => 'true_false', 'instructions' => 'Trafia na trzy płyty u góry strony Osiągnięcia (max 3 najnowsze z zaznaczonych)' ),
			array( 'key' => 'field_nagroda_opis', 'label' => 'Krótki opis', 'name' => 'opis', 'type' => 'textarea', 'rows' => 2, 'instructions' => 'Tylko dla wyróżnionych — tekst na płycie na stronie Osiągnięcia.' ),
			array( 'key' => 'field_nagroda_projekt_nazwa', 'label' => 'Nazwa projektu', 'name' => 'projekt_nazwa', 'type' => 'text', 'instructions' => 'Wyświetlana w kolumnie Projekt, nawet bez powiązanego wpisu poniżej.' ),
			array(
				'key'   => 'field_nagroda_projekt',
				'label' => 'Powiązany projekt',
				'name'  => 'projekt_powiazany',
				'type'  => 'relationship',
				'post_type' => array( 'projekt' ),
				'max'   => 1,
				'instructions' => 'Opcjonalne — jeśli ustawione, kolumna Projekt linkuje do tego wpisu zamiast pokazywać samą nazwę.',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'nagroda',
				),
			),
		),
	) );

	acf_add_local_field_group( array(
		'key'    => 'group_publikacja',
		'title'  => 'Publikacja — szczegóły',
		'fields' => array(
			array( 'key' => 'field_publikacja_typ', 'label' => 'Typ', 'name' => 'typ', 'type' => 'text' ),
			array( 'key' => 'field_publikacja_rok', 'label' => 'Rok', 'name' => 'rok', 'type' => 'number' ),
			array( 'key' => 'field_publikacja_link', 'label' => 'Link (artykuł lub skan PDF)', 'name' => 'link', 'type' => 'url' ),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'publikacja',
				),
			),
		),
	) );
}
