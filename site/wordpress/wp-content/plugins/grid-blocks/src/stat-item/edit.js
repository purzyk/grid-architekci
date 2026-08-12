import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { value, label } = attributes;
	const blockProps = useBlockProps( { className: 'grid-stat-item' } );

	return (
		<div { ...blockProps }>
			<RichText
				tagName="div"
				className="grid-stat-item__value"
				value={ value }
				onChange={ ( value ) => setAttributes( { value } ) }
				allowedFormats={ [] }
				placeholder="+20"
			/>
			<RichText
				tagName="div"
				className="grid-stat-item__label"
				value={ label }
				onChange={ ( label ) => setAttributes( { label } ) }
				allowedFormats={ [] }
				placeholder="etykieta"
			/>
		</div>
	);
}
