<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

if ( ! function_exists( 'grid_consent_choice' ) ) {
	return;
}

$choice = grid_consent_choice();
$policy_url = home_url( '/polityka-prywatnosci-i-plikow-cookies-oraz-regulamin/' );

// The GA4 measurement ID travels down as a data attribute rather than a
// separate localized script — view.js needs it once, to bootstrap gtag()
// itself if the visitor accepts mid-session (the PHP-side enqueue in
// grid-core already decided "no consent yet" for *this* page load, so
// accepting has to activate GA4 client-side rather than waiting for a
// reload). Gated on grid_ga4_eligible(), not just "does the constant
// exist" — the dev-host/signed-in-editor guards have to hold even when
// someone clicks Akceptuję, or accepting here would fire GA4 on the dev
// site regardless of what grid-core's own enqueue decided.
$ga4_id = ( defined( 'GRID_GA4_MEASUREMENT_ID' ) && function_exists( 'grid_ga4_eligible' ) && grid_ga4_eligible() )
	? GRID_GA4_MEASUREMENT_ID
	: '';
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<div
		id="grid-cookie-banner"
		data-ga-id="<?php echo esc_attr( $ga4_id ); ?>"
		class="fixed inset-x-0 bottom-0 z-50 border-t-2 border-divider bg-paper<?php echo '' !== $choice ? ' hidden' : ''; ?>"
	>
		<div class="mx-auto flex max-w-page flex-col items-start gap-3 px-[18px] py-4 sm:flex-row sm:items-center sm:justify-between sm:gap-6 sm:px-7 md:px-10">
			<p class="m-0 text-[13px] leading-[1.5] text-ink/70">
				Używamy plików cookie do analizy ruchu na stronie (Google Analytics). Możesz zaakceptować albo odrzucić — wybór możesz zmienić w każdej chwili w stopce strony. Szczegóły w
				<a class="text-ink underline hover:text-accent" href="<?php echo esc_url( $policy_url ); ?>">polityce prywatności i plików cookie</a>.
			</p>
			<div class="flex shrink-0 gap-4">
				<button type="button" data-cookie-action="denied" class="inline-flex cursor-pointer items-center justify-center text-[14px] font-extrabold text-ink/70 hover:text-ink">Odrzucam</button>
				<button type="button" data-cookie-action="granted" class="inline-flex cursor-pointer items-center justify-center gap-1.5 bg-accent px-[14.4px] py-2 text-[14px] font-extrabold text-paper hover:bg-accent-600 active:bg-accent-700">Akceptuję</button>
			</div>
		</div>
	</div>
</div>
