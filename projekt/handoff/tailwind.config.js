/** GRID Architekci — Tailwind config
 *  Generated from the design mocks. Every value here is used in the mocks;
 *  nothing is invented. Tailwind v3.
 */
module.exports = {
  content: ['./src/**/*.{html,js,jsx,ts,tsx,vue,php}'],
  theme: {
    // The mocks use exactly these three breakpoints — replaces Tailwind's defaults.
    screens: {
      sm: '620px',   // phone → tablet
      md: '900px',   // tablet → desktop
      lg: '1440px',  // desktop → the 1360px fixed canvas
    },
    extend: {
      colors: {
        accent: {
          DEFAULT: '#ff6633',  // links, interactive, hover mask
          600: '#e85320',      // hover
          700: '#c9420f',      // pressed, and accent text at body size
        },
        ink: {
          DEFAULT: '#201e1d',           // body text
          70: 'rgba(32,30,29,0.70)',    // secondary copy
          50: 'rgba(32,30,29,0.50)',    // labels, metadata
          45: 'rgba(32,30,29,0.45)',    // tertiary metadata
        },
        paper: '#f3f2f2',    // page surface
        desk: '#dedbd9',     // area around the page
        surface: '#eae9e9',  // image placeholder / plate fill
        divider: 'rgba(32,30,29,0.40)',  // the 2px rules
        hairline: '#d7d3d3',             // 1px table rows
      },
      fontFamily: {
        // One family throughout. Headings are the same face at weight 800.
        sans: ['Archivo', 'system-ui', 'sans-serif'],
      },
      fontSize: {
        // Named for role, not size — the mocks reuse these exact values.
        'label': ['10px', { lineHeight: '1.4', letterSpacing: '0.16em' }],
        'meta': ['11px', { lineHeight: '1.4', letterSpacing: '0.12em' }],
        'body-sm': ['13px', { lineHeight: '1.5' }],
        'body': ['15px', { lineHeight: '1.6' }],
        'lead': ['17px', { lineHeight: '1.45' }],
        'tile': ['17px', { lineHeight: '1.1', letterSpacing: '-0.015em' }],
        'h3': ['26px', { lineHeight: '1.05', letterSpacing: '-0.03em' }],
        'h2': ['34px', { lineHeight: '1.02', letterSpacing: '-0.035em' }],
        'h1': ['72px', { lineHeight: '0.94', letterSpacing: '-0.035em' }],
        'display': ['84px', { lineHeight: '0.92', letterSpacing: '-0.04em' }],
      },
      letterSpacing: {
        tightest: '-0.04em',
        tighter: '-0.035em',
        nav: '0.14em',
        kicker: '0.20em',
      },
      borderWidth: {
        // The system's rule weights: 2px major, 1px row separators.
        DEFAULT: '1px',
        2: '2px',
      },
      borderRadius: {
        // Nothing is rounded anywhere. Kept explicit so no one adds a radius.
        none: '0',
      },
      maxWidth: {
        page: '1360px',   // the design canvas
        measure: '760px', // longest comfortable line for the display heading
      },
      aspectRatio: {
        tile: '500 / 325',  // project thumbnails
        wide: '3 / 2',      // current-projects thumbnails
        cover: '3 / 4',     // publication covers
        portrait: '4 / 5',  // team photographs
      },
      transitionTimingFunction: {
        // The single easing used for every reveal in the mocks.
        reveal: 'cubic-bezier(.2,.7,.2,1)',
      },
      transitionDuration: {
        450: '450ms',
        600: '600ms',
        700: '700ms',
      },
      opacity: {
        28: '0.28',  // the accent hover mask over a thumbnail
      },
      saturate: {
        60: '.6',   // photographs at rest
        55: '.55',  // publication covers at rest
      },
    },
  },
  plugins: [],
};
