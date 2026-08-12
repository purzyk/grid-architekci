import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, Notice } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
	const { postsPerPage } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Ustawienia', 'grid' ) }>
					<RangeControl
						label={ __( 'Projektów przed „Pokaż więcej”', 'grid' ) }
						value={ postsPerPage }
						onChange={ ( postsPerPage ) => setAttributes( { postsPerPage } ) }
						min={ 3 }
						max={ 48 }
					/>
					<Notice status="info" isDismissible={ false }>
						{ __(
							'Filtrowanie i „Pokaż więcej” działają na opublikowanej stronie (Interactivity API) — ten podgląd jest statyczny.',
							'grid'
						) }
					</Notice>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender block="grid/project-grid" attributes={ attributes } />
		</div>
	);
}
