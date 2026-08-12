import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { value, label } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<RichText
				tagName="div"
				className="grid-stat-item__value text-[30px] font-extrabold leading-none tracking-[-0.03em] text-accent"
				value={ value }
				onChange={ ( value ) => setAttributes( { value } ) }
				allowedFormats={ [] }
				placeholder="+20"
			/>
			<RichText
				tagName="div"
				className="grid-stat-item__label mt-[5px] text-label uppercase tracking-nav text-ink/50"
				value={ label }
				onChange={ ( label ) => setAttributes( { label } ) }
				allowedFormats={ [] }
				placeholder="etykieta"
			/>
		</div>
	);
}
