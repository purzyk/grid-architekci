import { useBlockProps, RichText } from '@wordpress/block-editor';

const VARIANT_CLASSES = {
	accent: 'bg-accent text-paper',
	ink: 'bg-ink text-paper',
	surface: 'bg-surface text-ink',
};

export default function save( { attributes } ) {
	const { year, kicker, title, description, url, variant } = attributes;
	const blockProps = useBlockProps.save( {
		className: `group relative block overflow-hidden p-7 pb-[34px] ${ VARIANT_CLASSES[ variant ] }`,
	} );

	return (
		<a { ...blockProps } href={ url || '#' }>
			<RichText.Content
				tagName="div"
				className="grid-highlight-plate__year text-[46px] font-extrabold leading-none tracking-tightest"
				value={ year }
			/>
			<RichText.Content
				tagName="div"
				className="grid-highlight-plate__kicker my-3 text-label uppercase tracking-[0.18em] opacity-75"
				value={ kicker }
			/>
			<RichText.Content
				tagName="div"
				className="grid-highlight-plate__title text-[22px] font-extrabold uppercase leading-[1.1] tracking-[-0.02em]"
				value={ title }
			/>
			<RichText.Content
				tagName="p"
				className="grid-highlight-plate__description mt-2 text-body-sm opacity-80"
				value={ description }
			/>
			<span
				aria-hidden="true"
				className="pointer-events-none absolute bottom-[26px] right-6 -translate-x-2 text-[22px] font-extrabold opacity-0 transition-[opacity,transform] duration-450 ease-reveal group-hover:translate-x-0 group-hover:opacity-90"
			>→</span>
			<span
				aria-hidden="true"
				className="pointer-events-none absolute inset-0 bg-current opacity-0 transition-opacity duration-300 group-hover:opacity-[0.07]"
			></span>
		</a>
	);
}
