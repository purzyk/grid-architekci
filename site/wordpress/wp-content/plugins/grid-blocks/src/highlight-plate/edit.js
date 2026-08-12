import { useBlockProps, RichText, InspectorControls, URLInput } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const VARIANTS = [
	{ label: 'Accent', value: 'accent' },
	{ label: 'Ink', value: 'ink' },
	{ label: 'Surface', value: 'surface' },
];

const VARIANT_CLASSES = {
	accent: 'bg-accent text-paper',
	ink: 'bg-ink text-paper',
	surface: 'bg-surface text-ink',
};

export default function Edit( { attributes, setAttributes } ) {
	const { year, kicker, title, description, url, variant } = attributes;
	const blockProps = useBlockProps( {
		className: `group relative block overflow-hidden p-7 pb-[34px] ${ VARIANT_CLASSES[ variant ] }`,
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Wypełnienie', 'grid' ) }>
					<SelectControl
						label={ __( 'Wariant', 'grid' ) }
						value={ variant }
						options={ VARIANTS }
						onChange={ ( variant ) => setAttributes( { variant } ) }
					/>
					<p>{ __( 'Link docelowy', 'grid' ) }</p>
					<URLInput value={ url } onChange={ ( url ) => setAttributes( { url } ) } />
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<RichText
					tagName="div"
					className="grid-highlight-plate__year text-[46px] font-extrabold leading-none tracking-tightest"
					value={ year }
					onChange={ ( year ) => setAttributes( { year } ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="div"
					className="grid-highlight-plate__kicker my-3 text-label uppercase tracking-[0.18em] opacity-75"
					value={ kicker }
					onChange={ ( kicker ) => setAttributes( { kicker } ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="div"
					className="grid-highlight-plate__title text-[22px] font-extrabold uppercase leading-[1.1] tracking-[-0.02em]"
					value={ title }
					onChange={ ( title ) => setAttributes( { title } ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="p"
					className="grid-highlight-plate__description mt-2 text-body-sm opacity-80"
					value={ description }
					onChange={ ( description ) => setAttributes( { description } ) }
				/>
				<span className="pointer-events-none absolute bottom-[26px] right-6 -translate-x-2 text-[22px] font-extrabold opacity-0 transition-[opacity,transform] duration-450 ease-reveal">→</span>
			</div>
		</>
	);
}
