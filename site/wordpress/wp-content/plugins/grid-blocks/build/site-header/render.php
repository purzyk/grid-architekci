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

/*
 * Slid out of view while scrolling down, back in on the way up (view.js
 * toggles .is-hidden). The sticky positioning itself lives in the theme's
 * tailwind-input.css, on the template-part wrapper around this header —
 * see the comment there for why it can't be on this element. Two details
 * worth keeping here:
 *
 * - The negative margin/padding pair reproduces the page sheet's own top
 *   padding (pt-5 sm:pt-6 md:pt-[30px] on the wrapper in every template)
 *   *inside* the header. At rest that's visually identical to before; once
 *   stuck it means the paper band travels with the header instead of the
 *   logo sitting flush against the viewport edge. bg-paper is what the
 *   content then scrolls underneath.
 * - The :has() rule pins it open while the mobile menu is expanded —
 *   sliding away with the nav unfolded would take the open menu along.
 *   #grid-navtoggle lives inside the header, so peer-* can't reach it.
 */
$header_class = 'grid-site-header pointer-events-auto -mt-5 flex flex-wrap items-center justify-between gap-x-6 gap-y-3 border-b-2 border-divider bg-paper pb-3.5 pt-5 transition-transform duration-300 ease-reveal [&.is-hidden]:-translate-y-full [&:has(#grid-navtoggle:checked)]:translate-y-0 motion-reduce:transition-none sm:-mt-6 sm:pt-6 md:-mt-[30px] md:flex-nowrap md:items-end md:pt-[30px]';
?>
<header <?php echo get_block_wrapper_attributes( array( 'class' => $header_class ) ); ?>>
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
				class="border-b pb-0.5 text-meta uppercase tracking-nav hover:text-ink <?php echo $item['current'] ? 'border-accent text-ink' : 'border-transparent text-ink/75'; ?>"
			><?php echo esc_html( $item['label'] ); ?></a>
		<?php endforeach; ?>
	</nav>
</header>
