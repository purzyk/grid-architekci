import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { year, kicker, title, description, url, variant } = attributes;
	const blockProps = useBlockProps.save( {
		className: `grid-highlight-plate is-variant-${ variant }`,
	} );

	return (
		<a { ...blockProps } href={ url || '#' }>
			<RichText.Content tagName="div" className="grid-highlight-plate__year" value={ year } />
			<RichText.Content tagName="div" className="grid-highlight-plate__kicker" value={ kicker } />
			<RichText.Content tagName="div" className="grid-highlight-plate__title" value={ title } />
			<RichText.Content tagName="p" className="grid-highlight-plate__description" value={ description } />
			<span className="grid-highlight-plate__arrow" aria-hidden="true">→</span>
			<span className="grid-highlight-plate__mask" aria-hidden="true"></span>
		</a>
	);
}
