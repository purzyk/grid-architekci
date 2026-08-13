<?php
/**
 * One-off seed script: populates lead/opis/metryka for Dom Kultury,
 * Zapolice — the one project whose actual descriptive text survived in
 * the mock (projekt/grid-projekt.dc.html's own pp.* data, which reads as
 * real project copy, not placeholder text). Every other project has none
 * of this in the source migration data at all (confirmed via
 * grid-legacy/export.json) — this is the single example proving the
 * project-detail/project-header blocks render correctly with full data,
 * not a template for fabricating copy for the rest.
 * Run via: ddev wp eval-file wordpress/wp-content/seed-content/seed-project-content.php
 */

if ( ! function_exists( 'update_field' ) ) {
	echo "ACF not active — aborting.\n";
	return;
}

$post = get_page_by_path( 'dom-kultury-zapolice', OBJECT, 'projekt' );
if ( ! $post ) {
	echo "No projekt post found for dom-kultury-zapolice\n";
	return;
}
$post_id = $post->ID;

update_field( 'field_projekt_lead', 'Całkowita przebudowa gminnego ośrodka sportu i kultury. Forma jest wprost odwzorowaniem podziałów funkcjonalnych wewnątrz budynku.', $post_id );

$opis  = '<p>Efektem jest parterowy budynek złożony z trzech brył połączonych nieregularnym hallem. Wejście wycofano od ulicy, by powstał plac wejściowy — przedpole z zielenią i siedziskami, przestrzeń publiczna otwarta dla wszystkich, także wtedy, gdy budynek jest zamknięty.</p>';
$opis .= '<p>Przy strefie wejściowej zlokalizowano bibliotekę, która razem z hallem tworzy strefę przyjęć. Kolejne skrzydło mieści dwie sale zajęć fakultatywnych oraz salę konferencyjną i muzyczną, doświetlone wewnętrznym patio. Największa bryła to sala wielofunkcyjna dla 250 osób ze sceną i rozkładaną widownią — przystosowana również do zajęć sportowych i dzielona na dwie niezależne przestrzenie.</p>';
update_field( 'field_projekt_opis', $opis, $post_id );

update_field( 'field_projekt_metryka', array(
	'klient' => 'Gmina Zapolice',
	'zakres' => 'Koncepcja, PFU',
	'zespol' => 'Artur Toboła, Agnieszka Zając, Damian Garbula',
	// konstrukcja/instalacje/wykonawca/autor_zdjec left empty — the mock
	// itself marks these "do uzupełnienia" (still outstanding), not a gap
	// in this seed script.
), $post_id );

echo "Updated lead/opis/metryka for Dom Kultury, Zapolice (ID {$post_id}).\n";
