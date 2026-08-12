import { useBlockProps, useInnerBlocksProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { heading, intro } = attributes;
	const blockProps = useBlockProps.save( { className: 'grid-hero' } );
	const innerBlocksProps = useInnerBlocksProps.save( { className: 'grid-hero__stats' } );

	return (
		<div { ...blockProps }>
			<RichText.Content tagName="h1" className="grid-hero__heading" value={ heading } />
			<div className="grid-hero__side">
				<RichText.Content tagName="p" className="grid-hero__intro" value={ intro } />
				<div { ...innerBlocksProps } />
			</div>
		</div>
	);
}
