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
		get isTileHidden() {
			const context = getContext();
			const inCategory =
				context.filterCategory === 'all' ||
				context.filterCategory === context.itemCategory;

			if ( ! inCategory ) {
				return true;
			}

			return ! context.showAll && state.tileIndex >= context.limit;
		},
		get isTileWide() {
			return state.tileIndex % 7 === 0;
		},
		get visibleCount() {
			const context = getContext();
			const total = context.categoryCounts[ context.filterCategory ] ?? 0;
			return context.showAll ? total : Math.min( total, context.limit );
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
			return getContext().showAll
				? 'Pokaż mniej'
				: 'Pokaż więcej projektów';
		},
	},
	actions: {
		setCategory: () => {
			const context = getContext();
			context.filterCategory = context.filterValue;
		},
		toggleShowAll: () => {
			const context = getContext();
			context.showAll = ! context.showAll;
		},
	},
} );
