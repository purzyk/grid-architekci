import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

// No ServerSideRender here on purpose — same reasoning as the other
// render.php-only blocks in this project: it's a handful of fixed strings,
// not worth previewing inline (and 404.html is never opened in the editor
// as content anyway).
export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<p style={ { margin: 0, padding: '12px', border: '1px dashed #ccc', fontSize: '12px' } }>
				{ __(
					'404 — treść: kicker, nagłówek, opis i link powrotny. Treść ustawiona na stałe w render.php.',
					'grid'
				) }
			</p>
		</div>
	);
}
