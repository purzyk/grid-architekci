import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { n, title, description } = attributes;
	const blockProps = useBlockProps.save( { className: 'border-t-2 border-accent pt-3' } );

	return (
		<div { ...blockProps }>
			<RichText.Content
				tagName="div"
				className="grid-process-step__n text-[12px] font-extrabold tracking-[0.14em] text-accent"
				value={ n }
			/>
			<RichText.Content
				tagName="h3"
				className="grid-process-step__title mb-2 mt-2.5 text-[19px] font-extrabold uppercase leading-[1.12] tracking-[-0.02em]"
				value={ title }
			/>
			<RichText.Content
				tagName="p"
				className="grid-process-step__description m-0 text-[13px] leading-[1.55] text-ink/70"
				value={ description }
			/>
		</div>
	);
}
