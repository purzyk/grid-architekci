import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { statement, body } = attributes;
	const blockProps = useBlockProps.save( { className: 'grid-manifesto' } );

	return (
		<div { ...blockProps }>
			<RichText.Content tagName="h1" className="grid-manifesto__statement" value={ statement } />
			<RichText.Content tagName="p" className="grid-manifesto__body" value={ body } />
		</div>
	);
}
