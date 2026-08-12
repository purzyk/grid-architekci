import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { statement, body } = attributes;
	const blockProps = useBlockProps( { className: 'grid-manifesto' } );

	return (
		<div { ...blockProps }>
			<RichText
				tagName="h1"
				className="grid-manifesto__statement"
				value={ statement }
				onChange={ ( statement ) => setAttributes( { statement } ) }
				placeholder="Zdanie otwierające…"
			/>
			<RichText
				tagName="p"
				className="grid-manifesto__body"
				value={ body }
				onChange={ ( body ) => setAttributes( { body } ) }
				placeholder="Akapit…"
			/>
		</div>
	);
}
