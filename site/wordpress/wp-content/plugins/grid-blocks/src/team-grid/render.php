<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$label = $attributes['label'] ?? '';
$note  = $attributes['note'] ?? '';

$query = new WP_Query( array(
	'post_type'      => 'zespol',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
) );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'grid-team-grid' ) ); ?>>
	<div class="grid-team-grid__head">
		<?php if ( $label ) : ?>
			<h2 class="grid-team-grid__label"><?php echo esc_html( $label ); ?></h2>
		<?php endif; ?>
		<?php if ( $note ) : ?>
			<span class="grid-team-grid__note"><?php echo esc_html( $note ); ?></span>
		<?php endif; ?>
	</div>
	<div class="grid-team-grid__grid">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php
			$stanowisko = function_exists( 'get_field' ) ? get_field( 'stanowisko' ) : '';
			$funkcja    = function_exists( 'get_field' ) ? get_field( 'funkcja' ) : '';
			$bio        = function_exists( 'get_field' ) ? get_field( 'bio' ) : '';
			$role       = trim( implode( ' · ', array_filter( array( $stanowisko, $funkcja ) ) ) );
			$photo_url  = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
			?>
			<div class="grid-team-grid__member">
				<?php if ( $photo_url ) : ?>
					<div class="grid-team-grid__photo" style="background-image:url(<?php echo esc_url( $photo_url ); ?>)"></div>
				<?php else : ?>
					<div class="grid-team-grid__photo grid-team-grid__photo--empty"></div>
				<?php endif; ?>
				<div class="grid-team-grid__name"><?php the_title(); ?></div>
				<?php if ( $role ) : ?>
					<div class="grid-team-grid__role"><?php echo esc_html( $role ); ?></div>
				<?php endif; ?>
				<?php if ( $bio ) : ?>
					<p class="grid-team-grid__bio"><?php echo esc_html( $bio ); ?></p>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
</div>
<?php wp_reset_postdata(); ?>
