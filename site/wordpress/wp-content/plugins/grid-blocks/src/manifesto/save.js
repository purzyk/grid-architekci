import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { statement, body } = attributes;
	const blockProps = useBlockProps.save( {
		className:
			'grid grid-cols-1 items-start gap-7 pb-9 pt-10 md:grid-cols-[minmax(0,1fr)_420px] md:gap-16 md:pb-10 md:pt-14',
	} );

	return (
		<div { ...blockProps }>
			<RichText.Content
				tagName="h1"
				className="grid-manifesto__statement m-0 hyphens-auto break-words text-[clamp(28px,4.2vw,54px)] leading-[1.05] tracking-[-0.033em]"
				value={ statement }
			/>
			<RichText.Content
				tagName="p"
				className="grid-manifesto__body m-0 text-[15px] leading-[1.65] text-ink/75"
				value={ body }
			/>
		</div>
	);
}
