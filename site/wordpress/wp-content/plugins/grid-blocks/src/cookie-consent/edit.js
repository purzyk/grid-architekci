import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

// No ServerSideRender here on purpose — the real thing is a fixed overlay
// pinned to the bottom of the viewport, which previews badly (and
// pointlessly) inline in the editor canvas.
export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<p style={ { margin: 0, padding: '12px', border: '1px dashed #ccc', fontSize: '12px' } }>
				{ __(
					'Banner cookies (RODO) — pasek na dole strony, widoczny tylko na froncie dopóki odwiedzający nie wybierze Akceptuję/Odrzucam. Treść i przyciski ustawione na stałe w render.php.',
					'grid'
				) }
			</p>
		</div>
	);
}
