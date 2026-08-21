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
$metryka    = get_field( 'metryka', $post_id ) ?: array(); // repeater: array of {label, value}

// Rok and status aren't part of the metryka repeater (rok is its own field,
// status is a taxonomy) but the mocks show them as metric columns too —
// prepend them so the grid matches what the design actually shows.
$metric_rows = array();
$rok = get_field( 'rok', $post_id );
if ( $rok ) {
	$metric_rows[] = array( 'label' => 'Rok', 'value' => $rok );
}
$status_terms = get_the_terms( $post_id, 'projekt_status' );
if ( $status_terms && ! is_wp_error( $status_terms ) ) {
	$metric_rows[] = array( 'label' => 'Status', 'value' => $status_terms[0]->name );
}
foreach ( $metryka as $row ) {
	if ( ! empty( $row['label'] ) && ! empty( $row['value'] ) ) {
		$metric_rows[] = array( 'label' => $row['label'], 'value' => $row['value'] );
	}
}

// The mock's photos and site-plan bleed edge-to-edge with the page canvas
// (its own outer wrapper carries no horizontal padding — every section
// adds its own instead). Our page-shell already applies that padding once
// at the top level, so breaking these specific pieces out to true full
// bleed needs an equal-and-opposite negative margin — same technique used
// for the O nas/Kontakt photos.
$bleed_class      = '-mx-[18px] sm:-mx-7 md:-mx-10';
$photo_class      = 'block h-[260px] w-full object-cover saturate-60 sm:h-[400px] md:h-[620px]';
$photo_class_half = 'block h-[260px] w-full object-cover saturate-60 sm:h-[340px] md:h-[430px]';

// The featured thumbnail (masonry_featured_image from the old theme) is
// very often a pre-shrunk 500px legacy crop, while gallery photos were
// sideloaded individually and are reliably full-res — use the gallery's
// own first photo as the hero whenever there is one, and only fall back
// to the featured thumbnail for the sparse projects that have no gallery
// at all.
$galeria_list = ! empty( $galeria ) ? array_values( $galeria ) : array();
$hero_id      = null;
if ( $galeria_list ) {
	$first_img = array_shift( $galeria_list );
	$hero_id   = is_array( $first_img ) ? ( $first_img['ID'] ?? null ) : $first_img;
}
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php if ( $hero_id ) : ?>
		<div class="<?php echo esc_attr( $bleed_class ); ?>">
			<?php echo wp_get_attachment_image( $hero_id, 'large', false, array( 'class' => $photo_class ) ); ?>
		</div>
	<?php elseif ( has_post_thumbnail( $post_id ) ) : ?>
		<div class="<?php echo esc_attr( $bleed_class ); ?>">
			<?php echo get_the_post_thumbnail( $post_id, 'large', array( 'class' => $photo_class ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $opis ) : ?>
		<?php
		// "Opis (dwa akapity)" is meant as two explicit paragraphs, one per
		// column — the mock hardcodes body1/body2 into their own grid cells,
		// not a CSS multi-column flow (which would split wherever a column
		// runs out of height, ignoring paragraph boundaries). Split the real
		// <p> tags instead so paragraph 1 always lands left, the rest right.
		preg_match_all( '/<p[^>]*>.*?<\/p>/is', $opis, $opis_matches );
		$opis_paragraphs = $opis_matches[0] ?: array( $opis );
		$opis_left       = $opis_paragraphs[0];
		$opis_right      = implode( '', array_slice( $opis_paragraphs, 1 ) );
		?>
		<div class="mt-9 grid grid-cols-1 gap-6 text-body text-ink/70 md:mt-12 md:grid-cols-2 md:gap-14">
			<div class="[&_p]:mb-4 [&_p:last-child]:mb-0"><?php echo wp_kses_post( $opis_left ); ?></div>
			<?php if ( $opis_right ) : ?>
				<div class="[&_p]:mb-4 [&_p:last-child]:mb-0"><?php echo wp_kses_post( $opis_right ); ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php
	// ACF's gallery field normally returns full image arrays, but falls back
	// to plain attachment IDs if the field was ever saved as raw IDs (e.g.
	// via update_field() with an ID array, as the migration seed script
	// does) — handle both.
	$grid_items = array();
	foreach ( $galeria_list as $img ) {
		$img_id = is_array( $img ) ? ( $img['ID'] ?? null ) : $img;
		if ( $img_id ) {
			$grid_items[] = array( 'type' => 'photo', 'id' => $img_id );
		}
	}

	// The site-plan drawing is spliced into the same grid the photos render
	// in, rather than always trailing the gallery — editors think of it as
	// "which numbered photo", with the hero counting as 1, so position 3
	// (the default) lands it as the grid's own first item.
	if ( $rysunek_id ) {
		$rysunek_pozycja = (int) ( get_field( 'rysunek_pozycja', $post_id ) ?: 3 );
		$insert_at       = max( 0, min( count( $grid_items ), $rysunek_pozycja - 2 ) );
		array_splice( $grid_items, $insert_at, 0, array( array( 'type' => 'rysunek', 'id' => $rysunek_id ) ) );
	}
	?>

	<?php if ( ! empty( $grid_items ) ) : ?>
		<div class="mt-9 grid grid-cols-2 gap-0.5 <?php echo esc_attr( $bleed_class ); ?>">
			<?php foreach ( $grid_items as $i => $item ) : ?>
				<?php
				// Alternate full-width / paired-half-width, matching the
				// mock: every 3rd item runs full width, the two in between
				// sit side by side. The rysunek follows the same rhythm as
				// a regular photo, just with its own box/blend treatment.
				$is_full = ( 0 === $i % 3 );
				if ( 'rysunek' === $item['type'] ) :
					$box_class = $is_full
						? 'flex h-[260px] w-full items-center justify-center bg-white p-4 sm:h-[400px] md:h-[620px] md:p-5 col-span-2'
						: 'flex h-[260px] w-full items-center justify-center bg-white p-4 sm:h-[340px] md:h-[430px] md:p-5';
					?>
					<div class="<?php echo esc_attr( $box_class ); ?>">
						<?php echo wp_get_attachment_image( $item['id'], 'large', false, array( 'class' => 'max-h-full max-w-full mix-blend-multiply' ) ); ?>
					</div>
				<?php else :
					$class = $is_full ? $photo_class . ' col-span-2' : $photo_class_half;
					echo wp_get_attachment_image( $item['id'], 'large', false, array( 'class' => $class ) );
				endif;
				?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $metric_rows ) ) : ?>
		<div class="mt-12 grid grid-cols-2 gap-x-6 gap-y-[26px] border-t-2 border-divider py-[26px] sm:grid-cols-3 md:grid-cols-5">
			<?php foreach ( $metric_rows as $row ) : ?>
				<div>
					<div class="mb-1.5 h-3 text-label uppercase tracking-kicker text-ink/50"><?php echo esc_html( $row['label'] ); ?></div>
					<div class="text-body-sm leading-[1.4]"><?php echo esc_html( $row['value'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
