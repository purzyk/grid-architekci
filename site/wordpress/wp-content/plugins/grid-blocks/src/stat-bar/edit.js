import { useBlockProps, useInnerBlocksProps, InnerBlocks } from '@wordpress/block-editor';

const TEMPLATE = [
	[ 'grid/stat-item', { value: '+20', label: 'lat pracowni' } ],
	[ 'grid/stat-item', { value: '+200', label: 'projektów' } ],
	[ 'grid/stat-item', { value: '+20', label: 'nagród i wyróżnień' } ],
];

export default function Edit() {
	const blockProps = useBlockProps( { className: 'grid-stat-bar' } );
	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		template: TEMPLATE,
		allowedBlocks: [ 'grid/stat-item' ],
		renderAppender: InnerBlocks.ButtonBlockAppender,
	} );

	return <div { ...innerBlocksProps } />;
}
