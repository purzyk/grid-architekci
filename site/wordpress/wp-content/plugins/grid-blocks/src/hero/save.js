import { useBlockProps, useInnerBlocksProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { heading, intro } = attributes;
	const blockProps = useBlockProps.save( {
		className:
			'grid grid-cols-1 items-end gap-7 pb-6 pt-9 sm:pt-10 md:gap-10 md:pb-[30px] md:pt-14 lg:grid-cols-[minmax(0,1fr)_420px] lg:gap-16',
	} );
	const innerBlocksProps = useInnerBlocksProps.save( {} );

	return (
		<div { ...blockProps }>
			<RichText.Content
				tagName="h1"
				className="grid-hero__heading m-0 hyphens-auto break-words text-[clamp(30px,6.4vw,84px)] font-extrabold uppercase leading-[0.94] tracking-[-0.04em] lg:max-w-measure"
				value={ heading }
			/>
			<div>
				<RichText.Content
					tagName="p"
					className="grid-hero__intro m-0 mb-[18px] text-[15px] leading-[1.6] text-ink/75"
					value={ intro }
				/>
				<div { ...innerBlocksProps } />
			</div>
		</div>
	);
}
