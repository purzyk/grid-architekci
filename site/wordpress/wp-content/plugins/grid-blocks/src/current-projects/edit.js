import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { label } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Nagłówek sekcji', 'grid' ) }>
					<TextControl
						label={ __( 'Etykieta', 'grid' ) }
						value={ label }
						onChange={ ( label ) => setAttributes( { label } ) }
						help={ __( 'Które projekty się tu pokazują i w jakiej kolejności ustawia się w Ustawienia → Aktualne projekty, nie tutaj.', 'grid' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender block="grid/current-projects" attributes={ attributes } />
		</div>
	);
}
