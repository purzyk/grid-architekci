import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { limit } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Ustawienia', 'grid' ) }>
					<RangeControl
						label={ __( 'Liczba płyt', 'grid' ) }
						value={ limit }
						onChange={ ( limit ) => setAttributes( { limit } ) }
						min={ 1 }
						max={ 3 }
					/>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender block="grid/featured-awards" attributes={ attributes } />
		</div>
	);
}
