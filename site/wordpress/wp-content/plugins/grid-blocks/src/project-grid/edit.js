import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Notice } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes } ) {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Ustawienia', 'grid' ) }>
					<Notice status="info" isDismissible={ false }>
						{ __(
							'Filtrowanie działa na opublikowanej stronie (Interactivity API) — ten podgląd jest statyczny.',
							'grid'
						) }
					</Notice>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender block="grid/project-grid" attributes={ attributes } />
		</div>
	);
}
