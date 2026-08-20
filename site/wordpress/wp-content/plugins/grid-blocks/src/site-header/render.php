<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$nav_items = array(
	array( 'label' => 'Projekty', 'url' => home_url( '/' ), 'current' => is_front_page() || is_singular( 'projekt' ) ),
	array( 'label' => 'O nas', 'url' => home_url( '/o-nas/' ), 'current' => is_page( 'o-nas' ) ),
	array( 'label' => 'Osiągnięcia', 'url' => home_url( '/osiagniecia/' ), 'current' => is_page( 'osiagniecia' ) ),
	array( 'label' => 'Kontakt', 'url' => home_url( '/kontakt/' ), 'current' => is_page( 'kontakt' ) ),
);
?>
<header <?php echo get_block_wrapper_attributes( array( 'class' => 'flex flex-wrap items-center justify-between gap-x-6 gap-y-3 border-b-2 border-divider pb-3.5 md:flex-nowrap md:items-end' ) ); ?>>
	<input id="grid-navtoggle" type="checkbox" class="peer/toggle hidden">

	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block md:order-1">
		<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo.png' ) ); ?>" alt="GRID Architekci" width="544" height="100" class="block h-7 w-auto md:h-8">
	</a>

	<label for="grid-navtoggle" class="-mr-1 cursor-pointer p-1 text-ink md:hidden md:order-3" aria-label="Menu">
		<svg class="icon-open block h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
		<svg class="icon-close hidden h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square"><path d="M5 5l14 14M19 5L5 19"/></svg>
	</label>

	<nav class="hidden w-full flex-col items-start gap-3 pb-1 pt-2 peer-checked/toggle:flex md:order-2 md:ml-auto md:flex md:w-auto md:flex-row md:flex-wrap md:items-center md:gap-7 md:pb-0 md:pt-0">
		<?php foreach ( $nav_items as $item ) : ?>
			<a
				href="<?php echo esc_url( $item['url'] ); ?>"
				<?php if ( $item['current'] ) : ?>aria-current="page"<?php endif; ?>
				class="border-b pb-0.5 text-meta uppercase tracking-nav hover:text-ink <?php echo $item['current'] ? 'border-accent text-ink' : 'border-transparent text-ink/50'; ?>"
			><?php echo esc_html( $item['label'] ); ?></a>
		<?php endforeach; ?>
	</nav>
</header>
