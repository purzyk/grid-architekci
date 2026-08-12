import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { n, title, description } = attributes;
	const blockProps = useBlockProps.save( { className: 'grid-process-step' } );

	return (
		<div { ...blockProps }>
			<RichText.Content tagName="div" className="grid-process-step__n" value={ n } />
			<RichText.Content tagName="h3" className="grid-process-step__title" value={ title } />
			<RichText.Content tagName="p" className="grid-process-step__description" value={ description } />
		</div>
	);
}
