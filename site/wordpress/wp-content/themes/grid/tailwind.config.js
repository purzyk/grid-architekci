/** GRID Architekci — Tailwind config
 *  Ported directly from projekt/handoff/tailwind.config.js — the mocks'
 *  own token source. Every value here is used in the mocks; nothing is
 *  invented. Tailwind v3.
 */
module.exports = {
	content: [
		'./templates/**/*.html',
		'./parts/**/*.html',
		'./*.php',
		'../../plugins/grid-blocks/src/**/*.{php,js}',
		'../../plugins/grid-core/**/*.php',
		// The actual seeded page content (Osiągnięcia/Kontakt's plain core-block
		// classes in particular) isn't duplicated in any block source file —
		// without this, those classes are silently never compiled.
		'../../seed-content/**/*.html',
		// seed-contact-form.php builds $label_class as a PHP string, never
		// duplicated in any scanned .html file — same gap as above, just in
		// a .php file this time. Learned this twice now; keeping it for good.
		'../../seed-content/**/*.php',
	],
	theme: {
		// The mocks use exactly these three breakpoints — replaces Tailwind's defaults.
		screens: {
			sm: '620px', // phone → tablet
			md: '900px', // tablet → desktop
			lg: '1440px', // desktop → the 1360px fixed canvas
		},
		extend: {
			colors: {
				// CSS-variable backed, so a `.dark` class on <html> repaints the
				// whole site — see tailwind-input.css for the :root/.dark token
				// values (ported from the mocks' own theme-toggle setup).
				accent: {
					DEFAULT: 'rgb(var(--c-accent) / <alpha-value>)', // links, interactive, hover mask
					600: 'var(--c-accent-600)', // hover
					700: 'var(--c-accent-700)', // pressed, and accent text at body size
				},
				ink: 'rgb(var(--c-ink) / <alpha-value>)', // body text; opacity modifiers (ink/70, ink/50…) cover secondary/metadata copy
				paper: 'rgb(var(--c-paper) / <alpha-value>)', // page surface
				desk: 'var(--c-desk)', // area around the page
				surface: 'var(--c-surface)', // image placeholder / plate fill
				divider: 'var(--c-divider)', // the 2px rules
				hairline: 'var(--c-hairline)', // 1px table rows
			},
			fontFamily: {
				// One family throughout. Headings are the same face at weight 800.
				sans: ['Archivo', 'system-ui', 'sans-serif'],
			},
			fontSize: {
				// Named for role, not size — the mocks reuse these exact values.
				label: ['10px', { lineHeight: '1.4', letterSpacing: '0.16em' }],
				meta: ['11px', { lineHeight: '1.4', letterSpacing: '0.12em' }],
				'body-sm': ['13px', { lineHeight: '1.5' }],
				body: ['15px', { lineHeight: '1.6' }],
				lead: ['17px', { lineHeight: '1.45' }],
				tile: ['17px', { lineHeight: '1.1', letterSpacing: '-0.015em' }],
				h3: ['26px', { lineHeight: '1.05', letterSpacing: '-0.03em' }],
				h2: ['34px', { lineHeight: '1.02', letterSpacing: '-0.035em' }],
				h1: ['72px', { lineHeight: '0.94', letterSpacing: '-0.035em' }],
				display: ['84px', { lineHeight: '0.92', letterSpacing: '-0.04em' }],
			},
			letterSpacing: {
				tightest: '-0.04em',
				tighter: '-0.035em',
				nav: '0.14em',
				kicker: '0.20em',
			},
			borderWidth: {
				DEFAULT: '1px',
				2: '2px',
			},
			borderRadius: {
				// Nothing is rounded anywhere. Kept explicit so no one adds a radius.
				none: '0',
			},
			maxWidth: {
				page: '1360px', // the design canvas
				measure: '760px', // longest comfortable line for the display heading
			},
			aspectRatio: {
				tile: '500 / 325', // project thumbnails
				wide: '3 / 2', // current-projects thumbnails
				cover: '3 / 4', // publication covers
				portrait: '4 / 5', // team photographs
			},
			transitionTimingFunction: {
				reveal: 'cubic-bezier(.2,.7,.2,1)',
			},
			transitionDuration: {
				450: '450ms',
				600: '600ms',
				700: '700ms',
			},
			opacity: {
				28: '0.28', // the accent hover mask over a thumbnail
			},
			saturate: {
				60: '.6', // photographs at rest
				55: '.55', // publication covers at rest
			},
		},
	},
	plugins: [],
};
