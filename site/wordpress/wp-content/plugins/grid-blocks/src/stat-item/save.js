import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { value, label } = attributes;
	const blockProps = useBlockProps.save( { className: 'grid-stat-item' } );

	return (
		<div { ...blockProps }>
			<RichText.Content tagName="div" className="grid-stat-item__value" value={ value } />
			<RichText.Content tagName="div" className="grid-stat-item__label" value={ label } />
		</div>
	);
}
