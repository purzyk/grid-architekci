import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { n, title, description } = attributes;
	const blockProps = useBlockProps( { className: 'grid-process-step' } );

	return (
		<div { ...blockProps }>
			<RichText
				tagName="div"
				className="grid-process-step__n"
				value={ n }
				onChange={ ( n ) => setAttributes( { n } ) }
				allowedFormats={ [] }
			/>
			<RichText
				tagName="h3"
				className="grid-process-step__title"
				value={ title }
				onChange={ ( title ) => setAttributes( { title } ) }
				allowedFormats={ [] }
			/>
			<RichText
				tagName="p"
				className="grid-process-step__description"
				value={ description }
				onChange={ ( description ) => setAttributes( { description } ) }
			/>
		</div>
	);
}
