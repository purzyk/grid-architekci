<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$label = $attributes['label'] ?? '';
$note  = $attributes['note'] ?? '';

$query = new WP_Query( array(
	'post_type'      => 'publikacja',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'meta_key'       => 'rok',
	'orderby'        => 'meta_value_num',
	'order'          => 'DESC',
) );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'grid-publications-grid' ) ); ?>>
	<?php if ( $label || $note ) : ?>
		<div class="grid-publications-grid__head">
			<?php if ( $label ) : ?><h2 class="grid-publications-grid__label"><?php echo esc_html( $label ); ?></h2><?php endif; ?>
			<?php if ( $note ) : ?><p class="grid-publications-grid__note"><?php echo esc_html( $note ); ?></p><?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="grid-publications-grid__grid">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php
			$post_id = get_the_ID();
			$typ     = function_exists( 'get_field' ) ? get_field( 'typ', $post_id ) : '';
			$rok     = function_exists( 'get_field' ) ? get_field( 'rok', $post_id ) : '';
			$link    = function_exists( 'get_field' ) ? get_field( 'link', $post_id ) : '';
			$cover   = get_the_post_thumbnail_url( $post_id, 'medium' );
			?>
			<a
				<?php if ( $link ) : ?>href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener"<?php else : ?>href="#"<?php endif; ?>
				class="grid-publications-grid__item"
			>
				<div class="grid-publications-grid__cover">
					<?php if ( $cover ) : ?>
						<div class="grid-publications-grid__img" style="background-image:url(<?php echo esc_url( $cover ); ?>)"></div>
					<?php endif; ?>
				</div>
				<div class="grid-publications-grid__title"><?php the_title(); ?></div>
				<?php if ( $typ || $rok ) : ?>
					<div class="grid-publications-grid__meta">
						<?php if ( $typ ) : ?><span><?php echo esc_html( $typ ); ?></span><?php endif; ?>
						<?php if ( $rok ) : ?><span><?php echo esc_html( $rok ); ?></span><?php endif; ?>
					</div>
				<?php endif; ?>
			</a>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</div>
