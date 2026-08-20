import { store, getContext } from '@wordpress/interactivity';

const { state } = store( 'grid/project-grid', {
	state: {
		get isPressed() {
			const context = getContext();
			return context.filterCategory === context.filterValue;
		},
		get isTileHidden() {
			const context = getContext();
			return ! (
				context.filterCategory === 'all' ||
				context.filterCategory === context.itemCategory
			);
		},
		get countLabel() {
			const context = getContext();
			return String( context.categoryCounts[ context.filterCategory ] ?? 0 );
		},
	},
	actions: {
		setCategory: () => {
			const context = getContext();
			context.filterCategory = context.filterValue;
		},
	},
} );
