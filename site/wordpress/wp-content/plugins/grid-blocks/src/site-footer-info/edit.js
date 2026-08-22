import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

// No ServerSideRender here on purpose — same reasoning as cookie-consent's
// edit.js: it's a footer strip of social links, address and copyright, not
// something worth previewing inline.
export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<p style={ { margin: 0, padding: '12px', border: '1px dashed #ccc', fontSize: '12px' } }>
				{ __(
					'Stopka — informacje: social linki, notka, adres i pasek copyright/polityka prywatności. Treść ustawiona na stałe w render.php.',
					'grid'
				) }
			</p>
		</div>
	);
}
