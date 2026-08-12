<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$query = new WP_Query( array(
	'post_type'      => 'nagroda',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'meta_key'       => 'rok',
	'orderby'        => 'meta_value_num',
	'order'          => 'DESC',
) );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'grid-awards-table' ) ); ?>>
	<div class="grid-awards-table__head" aria-hidden="true">
		<span>Rok</span>
		<span>Konkurs / nagroda</span>
		<span>Projekt</span>
		<span>Wynik</span>
	</div>
	<div class="grid-awards-table__rows">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php
			$post_id    = get_the_ID();
			$rok        = function_exists( 'get_field' ) ? get_field( 'rok', $post_id ) : '';
			$konkurs    = function_exists( 'get_field' ) ? get_field( 'konkurs', $post_id ) : get_the_title();
			$wynik      = function_exists( 'get_field' ) ? get_field( 'wynik', $post_id ) : '';
			$related    = function_exists( 'get_field' ) ? get_field( 'projekt_powiazany', $post_id ) : null;
			$related_id = is_array( $related ) && ! empty( $related ) ? $related[0] : null;
			$href       = $related_id ? get_permalink( $related_id ) : '';
			$tag        = $href ? 'a' : 'div';
			?>
			<<?php echo esc_html( $tag ); ?>
				<?php if ( $href ) : ?>href="<?php echo esc_url( $href ); ?>"<?php endif; ?>
				class="grid-awards-table__row"
			>
				<span class="grid-awards-table__year"><?php echo esc_html( $rok ); ?></span>
				<span class="grid-awards-table__name"><?php echo esc_html( $konkurs ); ?></span>
				<span class="grid-awards-table__project"><?php echo esc_html( $related_id ? get_the_title( $related_id ) : '' ); ?></span>
				<span class="grid-awards-table__result"><?php echo esc_html( $wynik ); ?></span>
			</<?php echo esc_html( $tag ); ?>>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</div>
