import { useBlockProps, RichText, InspectorControls, URLInput } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const VARIANTS = [
	{ label: 'Accent', value: 'accent' },
	{ label: 'Ink', value: 'ink' },
	{ label: 'Surface', value: 'surface' },
];

export default function Edit( { attributes, setAttributes } ) {
	const { year, kicker, title, description, url, variant } = attributes;
	const blockProps = useBlockProps( {
		className: `grid-highlight-plate is-variant-${ variant }`,
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
					className="grid-highlight-plate__year"
					value={ year }
					onChange={ ( year ) => setAttributes( { year } ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="div"
					className="grid-highlight-plate__kicker"
					value={ kicker }
					onChange={ ( kicker ) => setAttributes( { kicker } ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="div"
					className="grid-highlight-plate__title"
					value={ title }
					onChange={ ( title ) => setAttributes( { title } ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="p"
					className="grid-highlight-plate__description"
					value={ description }
					onChange={ ( description ) => setAttributes( { description } ) }
				/>
				<span className="grid-highlight-plate__arrow">→</span>
			</div>
		</>
	);
}
