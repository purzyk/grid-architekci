/**
 * Scroll-away header.
 *
 * Scrolling down slides the header out of view; the first upward scroll
 * slides it back in, wherever you are on the page. Near the top it is
 * always shown, so short flicks in the hero don't make it flicker.
 *
 * Deliberately a plain viewScript rather than the Interactivity API module
 * project-grid uses: this is presentational only, and it would otherwise
 * pull the interactivity runtime onto every page just to toggle one class.
 */
const header = document.querySelector( '.grid-site-header' );

if ( header ) {
	const HIDDEN = 'is-hidden';
	// Below this the header always stays put — its own height plus a little,
	// so it never hides while it's still partly in its resting place.
	const alwaysVisibleBelow = header.offsetHeight + 40;
	// Inertial scrolling reports sub-pixel deltas; without a floor the
	// direction flips back and forth and the header oscillates.
	const minDelta = 6;

	let lastY = window.scrollY;
	let queued = false;

	const update = () => {
		queued = false;

		const y = window.scrollY;
		const delta = y - lastY;

		if ( Math.abs( delta ) < minDelta ) {
			return;
		}

		if ( y <= alwaysVisibleBelow ) {
			header.classList.remove( HIDDEN );
		} else {
			header.classList.toggle( HIDDEN, delta > 0 );
		}

		lastY = y;
	};

	window.addEventListener(
		'scroll',
		() => {
			if ( ! queued ) {
				queued = true;
				window.requestAnimationFrame( update );
			}
		},
		{ passive: true }
	);

	// A hidden header still holds focusable links — tabbing into one has to
	// bring it back, or focus lands off-screen.
	header.addEventListener( 'focusin', () => header.classList.remove( HIDDEN ) );
}
