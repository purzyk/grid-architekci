import { useBlockProps, useInnerBlocksProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { label, note } = attributes;
	const blockProps = useBlockProps.save( { className: 'grid-process-steps' } );
	const innerBlocksProps = useInnerBlocksProps.save( { className: 'grid-process-steps__grid' } );

	return (
		<div { ...blockProps }>
			<div className="grid-process-steps__head">
				<RichText.Content tagName="h2" className="grid-process-steps__label" value={ label } />
				<RichText.Content tagName="p" className="grid-process-steps__note" value={ note } />
			</div>
			<div { ...innerBlocksProps } />
		</div>
	);
}
