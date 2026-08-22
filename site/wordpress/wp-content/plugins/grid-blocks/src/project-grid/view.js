import { store, getContext } from '@wordpress/interactivity';

const { state } = store( 'grid/project-grid', {
	state: {
		get isPressed() {
			const context = getContext();
			return context.filterCategory === context.filterValue;
		},
		// Position of this tile within whatever list is currently on screen.
		// Both the twelve-item limit and the wide tile are counted off the
		// filtered list, exactly as the mock does — so switching category
		// re-flows the layout rather than leaving gaps where hidden tiles
		// used to sit.
		get tileIndex() {
			const context = getContext();
			return context.filterCategory === 'all'
				? context.indexAll
				: context.indexCategory;
		},
		// Whether *this* category is the one currently expanded — not a
		// plain showAll boolean, deliberately: switching category should
		// always land back on the 12-item view with "Pokaż więcej" showing
		// again, and comparing against filterCategory gives that reset for
		// free instead of requiring setCategory to reach into and clear a
		// flag owned by a different context layer (writes from a filter
		// button's own data-wp-context don't reliably propagate up to
		// root-level state the way reads do).
		get isExpanded() {
			const context = getContext();
			return context.expandedCategory === context.filterCategory;
		},
		get isTileHidden() {
			const context = getContext();
			const inCategory =
				context.filterCategory === 'all' ||
				context.filterCategory === context.itemCategory;

			if ( ! inCategory ) {
				return true;
			}

			return ! state.isExpanded && state.tileIndex >= context.limit;
		},
		get isTileWide() {
			return state.tileIndex % 7 === 0;
		},
		get visibleCount() {
			const context = getContext();
			const total = context.categoryCounts[ context.filterCategory ] ?? 0;
			return state.isExpanded ? total : Math.min( total, context.limit );
		},
		get countLabel() {
			const context = getContext();
			const total = context.categoryCounts[ context.filterCategory ] ?? 0;
			return state.visibleCount + ' / ' + total;
		},
		get isMoreHidden() {
			const context = getContext();
			return ( context.categoryCounts[ context.filterCategory ] ?? 0 ) <= context.limit;
		},
		get moreLabel() {
			const context = getContext();
			return state.isExpanded ? context.lessLabel : context.moreLabel;
		},
	},
	actions: {
		setCategory: () => {
			const context = getContext();
			context.filterCategory = context.filterValue;
		},
		toggleShowAll: () => {
			const context = getContext();
			context.expandedCategory = state.isExpanded ? '' : context.filterCategory;
		},
	},
} );
