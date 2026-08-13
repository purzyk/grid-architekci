<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$post_id = $block->context['postId'] ?? get_the_ID();

if ( ! $post_id || ! function_exists( 'get_field' ) ) {
	return;
}

$opis       = get_field( 'opis', $post_id );
$galeria    = get_field( 'galeria', $post_id );
$rysunek_id = get_field( 'rysunek_zagospodarowania', $post_id );
$metryka    = get_field( 'metryka', $post_id ) ?: array();

// Rok and status aren't part of the metryka group (rok is its own field,
// status is a taxonomy) but the mocks show them as metric columns too —
// merge them in so the grid matches what the design actually shows.
$rok = get_field( 'rok', $post_id );
if ( $rok ) {
	$metryka = array( 'rok' => $rok ) + $metryka;
}
$status_terms = get_the_terms( $post_id, 'projekt_status' );
if ( $status_terms && ! is_wp_error( $status_terms ) ) {
	$metryka = array( 'status' => $status_terms[0]->name ) + $metryka;
}

$metric_labels = array(
	'rok'         => 'Rok',
	'status'      => 'Status',
	'klient'      => 'Klient',
	'zakres'      => 'Zakres',
	'zespol'      => 'Zespół',
	'konstrukcja' => 'Konstrukcja',
	'instalacje'  => 'Instalacje',
	'wykonawca'   => 'Wykonawca',
	'autor_zdjec' => 'Autor zdjęć',
);

// The mock's photos and site-plan bleed edge-to-edge with the page canvas
// (its own outer wrapper carries no horizontal padding — every section
// adds its own instead). Our page-shell already applies that padding once
// at the top level, so breaking these specific pieces out to true full
// bleed needs an equal-and-opposite negative margin — same technique used
// for the O nas/Kontakt photos.
$bleed_class = '-mx-[18px] sm:-mx-7 md:-mx-10';
$photo_class = 'block h-[260px] w-full object-cover saturate-60 sm:h-[400px] md:h-[620px]';
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php if ( has_post_thumbnail( $post_id ) ) : ?>
		<div class="<?php echo esc_attr( $bleed_class ); ?>">
			<?php echo get_the_post_thumbnail( $post_id, 'large', array( 'class' => $photo_class ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $opis ) : ?>
		<div class="mt-9 columns-1 gap-14 text-body text-ink/70 md:mt-12 md:columns-2 [&_p]:mb-4 [&_p:last-child]:mb-0"><?php echo wp_kses_post( $opis ); ?></div>
	<?php endif; ?>

	<?php if ( ! empty( $galeria ) ) : ?>
		<div class="mt-9 flex flex-col gap-0.5 <?php echo esc_attr( $bleed_class ); ?>">
			<?php foreach ( $galeria as $img ) : ?>
				<?php
				// ACF's gallery field normally returns full image arrays, but
				// falls back to plain attachment IDs if the field was ever
				// saved as raw IDs (e.g. via update_field() with an ID array,
				// as the migration seed script does) — handle both.
				$img_id = is_array( $img ) ? ( $img['ID'] ?? null ) : $img;
				if ( $img_id ) {
					echo wp_get_attachment_image( $img_id, 'large', false, array( 'class' => $photo_class ) );
				}
				?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $rysunek_id ) : ?>
		<div class="mt-0.5 flex h-[260px] w-full items-center justify-center bg-paper p-4 sm:h-[340px] md:h-[430px] md:p-5 <?php echo esc_attr( $bleed_class ); ?>">
			<?php echo wp_get_attachment_image( $rysunek_id, 'large', false, array( 'class' => 'max-h-full max-w-full mix-blend-multiply' ) ); ?>
		</div>
	<?php endif; ?>

	<?php
	$has_metrics = is_array( $metryka ) && count( array_filter( $metryka ) ) > 0;
	if ( $has_metrics ) :
		?>
		<div class="mt-12 grid grid-cols-2 gap-x-6 gap-y-[26px] border-t-2 border-divider py-[26px] sm:grid-cols-3 md:grid-cols-5">
			<?php foreach ( $metric_labels as $key => $label ) : ?>
				<?php if ( empty( $metryka[ $key ] ) ) continue; ?>
				<div>
					<div class="mb-1.5 h-3 text-label uppercase tracking-kicker text-ink/50"><?php echo esc_html( $label ); ?></div>
					<div class="text-body-sm leading-[1.4]"><?php echo esc_html( $metryka[ $key ] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
