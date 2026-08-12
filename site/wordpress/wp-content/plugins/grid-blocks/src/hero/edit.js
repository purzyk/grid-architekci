import { useBlockProps, useInnerBlocksProps, RichText } from '@wordpress/block-editor';

const TEMPLATE = [ [ 'grid/stat-bar' ] ];

export default function Edit( { attributes, setAttributes } ) {
	const { heading, intro } = attributes;
	const blockProps = useBlockProps( {
		className:
			'grid grid-cols-1 items-end gap-[26px] pb-[26px] pt-10 md:grid-cols-[1fr_420px] md:gap-16 md:pb-[30px] md:pt-14',
	} );
	// No className here — grid/stat-bar (the only allowed child) already
	// renders its own complete wrapper div; adding classes here would
	// duplicate them on an extra, redundant wrapper.
	const innerBlocksProps = useInnerBlocksProps(
		{},
		{
			template: TEMPLATE,
			allowedBlocks: [ 'grid/stat-bar' ],
			templateLock: 'all',
		}
	);

	return (
		<div { ...blockProps }>
			<RichText
				tagName="h1"
				className="grid-hero__heading m-0 text-[36px] font-extrabold uppercase leading-[0.92] tracking-tightest sm:text-[52px] md:max-w-measure md:text-display"
				value={ heading }
				onChange={ ( heading ) => setAttributes( { heading } ) }
				allowedFormats={ [] }
			/>
			<div>
				<RichText
					tagName="p"
					className="grid-hero__intro mb-[18px] text-body text-ink/70"
					value={ intro }
					onChange={ ( intro ) => setAttributes( { intro } ) }
					placeholder="Krótki wstęp…"
				/>
				<div { ...innerBlocksProps } />
			</div>
		</div>
	);
}
