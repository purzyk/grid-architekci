import { useBlockProps, useInnerBlocksProps, InnerBlocks } from '@wordpress/block-editor';

const TEMPLATE = [
	[ 'grid/stat-item', { value: '+20', label: 'lat pracowni' } ],
	[ 'grid/stat-item', { value: '+200', label: 'projektów' } ],
	[ 'grid/stat-item', { value: '+20', label: 'nagród i wyróżnień' } ],
];

const CLASS_NAME = 'flex gap-[22px] border-t-2 border-divider pt-3.5 md:gap-10';

export default function Edit() {
	const blockProps = useBlockProps( { className: CLASS_NAME } );
	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		template: TEMPLATE,
		allowedBlocks: [ 'grid/stat-item' ],
		orientation: 'horizontal',
		renderAppender: InnerBlocks.ButtonBlockAppender,
	} );

	return <div { ...innerBlocksProps } />;
}
