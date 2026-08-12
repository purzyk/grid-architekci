import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { label, note } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Nagłówek sekcji', 'grid' ) }>
					<TextControl
						label={ __( 'Etykieta', 'grid' ) }
						value={ label }
						onChange={ ( label ) => setAttributes( { label } ) }
					/>
					<TextControl
						label={ __( 'Notatka', 'grid' ) }
						value={ note }
						onChange={ ( note ) => setAttributes( { note } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender block="grid/team-grid" attributes={ attributes } />
		</div>
	);
}
