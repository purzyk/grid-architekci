import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { value, label } = attributes;
	const blockProps = useBlockProps.save();

	return (
		<div { ...blockProps }>
			<RichText.Content
				tagName="div"
				className="grid-stat-item__value text-[26px] font-extrabold leading-none tracking-[-0.03em] text-accent sm:text-[30px]"
				value={ value }
			/>
			<RichText.Content
				tagName="div"
				className="grid-stat-item__label mt-[5px] text-label uppercase tracking-nav text-ink/50"
				value={ label }
			/>
		</div>
	);
}
