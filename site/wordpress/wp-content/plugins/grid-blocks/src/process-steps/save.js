import { useBlockProps, useInnerBlocksProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { label, note } = attributes;
	const blockProps = useBlockProps.save( { className: 'mt-12 md:mt-[60px]' } );
	const innerBlocksProps = useInnerBlocksProps.save( {
		className: 'mt-7 grid grid-cols-1 gap-7 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 lg:gap-7',
	} );

	return (
		<div { ...blockProps }>
			<div className="flex flex-col gap-2 border-b-2 border-divider pb-3.5 md:flex-row md:items-baseline md:justify-between">
				<RichText.Content
					tagName="h2"
					className="grid-process-steps__label m-0 text-[26px] font-extrabold uppercase tracking-[-0.035em] sm:text-[30px] md:text-[34px]"
					value={ label }
				/>
				<RichText.Content
					tagName="p"
					className="grid-process-steps__note m-0 max-w-[440px] text-[13px] text-ink/60 md:text-right"
					value={ note }
				/>
			</div>
			<div { ...innerBlocksProps } />
		</div>
	);
}
