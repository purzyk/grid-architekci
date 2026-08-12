import { useBlockProps, useInnerBlocksProps, RichText } from '@wordpress/block-editor';

const TEMPLATE = [ [ 'grid/stat-bar' ] ];

export default function Edit( { attributes, setAttributes } ) {
	const { heading, intro } = attributes;
	const blockProps = useBlockProps( { className: 'grid-hero' } );
	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'grid-hero__stats' },
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
				className="grid-hero__heading"
				value={ heading }
				onChange={ ( heading ) => setAttributes( { heading } ) }
				allowedFormats={ [] }
			/>
			<div className="grid-hero__side">
				<RichText
					tagName="p"
					className="grid-hero__intro"
					value={ intro }
					onChange={ ( intro ) => setAttributes( { intro } ) }
					placeholder="Krótki wstęp…"
				/>
				<div { ...innerBlocksProps } />
			</div>
		</div>
	);
}
