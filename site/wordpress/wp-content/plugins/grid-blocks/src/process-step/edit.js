import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { n, title, description } = attributes;
	const blockProps = useBlockProps( { className: 'border-t-2 border-accent pt-3' } );

	return (
		<div { ...blockProps }>
			<RichText
				tagName="div"
				className="grid-process-step__n text-[12px] font-extrabold tracking-[0.14em] text-accent"
				value={ n }
				onChange={ ( n ) => setAttributes( { n } ) }
				allowedFormats={ [] }
			/>
			<RichText
				tagName="h3"
				className="grid-process-step__title mb-2 mt-2.5 text-[19px] font-extrabold uppercase leading-[1.12] tracking-[-0.02em]"
				value={ title }
				onChange={ ( title ) => setAttributes( { title } ) }
				allowedFormats={ [] }
			/>
			<RichText
				tagName="p"
				className="grid-process-step__description m-0 text-[13px] leading-[1.55] text-ink/70"
				value={ description }
				onChange={ ( description ) => setAttributes( { description } ) }
			/>
		</div>
	);
}
