import { store, getContext } from '@wordpress/interactivity';

const { state } = store( 'grid/project-grid', {
	state: {
		get isPressed() {
			const context = getContext();
			return context.filterCategory === context.filterValue;
		},
		get isTileHidden() {
			const context = getContext();
			const categoryMatches =
				context.filterCategory === 'all' ||
				context.filterCategory === context.itemCategory;
			const withinVisibleCount = context.itemIndex < context.visibleCount;
			return ! ( categoryMatches && withinVisibleCount );
		},
		get currentFilterTotal() {
			const context = getContext();
			return context.categoryCounts[ context.filterCategory ] ?? 0;
		},
		get countLabel() {
			const context = getContext();
			const total = state.currentFilterTotal;
			const shown = Math.min( context.visibleCount, total );
			return `${ shown } / ${ total }`;
		},
		get showMoreLabel() {
			const context = getContext();
			const total = state.currentFilterTotal;
			return context.visibleCount >= total ? 'Pokaż mniej' : 'Pokaż więcej projektów';
		},
		get isShowMoreHidden() {
			const context = getContext();
			// Nothing to ever expand for this filter — hide rather than show a
			// "Pokaż mniej" that has nothing collapsed behind it.
			return state.currentFilterTotal <= context.initialVisibleCount;
		},
	},
	actions: {
		setCategory: () => {
			const context = getContext();
			context.filterCategory = context.filterValue;
			context.visibleCount = context.initialVisibleCount;
		},
		toggleShowMore: () => {
			const context = getContext();
			const total = state.currentFilterTotal;
			context.visibleCount =
				context.visibleCount >= total ? context.initialVisibleCount : total;
		},
	},
} );
