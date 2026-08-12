import { useBlockProps, useInnerBlocksProps, InnerBlocks, RichText } from '@wordpress/block-editor';

const TEMPLATE = [
	[ 'grid/process-step', { n: '01', title: 'Rozmowa i działka', description: 'Spotkanie, oględziny działki, warunki zabudowy lub plan miejscowy.' } ],
	[ 'grid/process-step', { n: '02', title: 'Koncepcja', description: 'Dwa warianty układu i bryły, rzuty i widoki 3D.' } ],
	[ 'grid/process-step', { n: '03', title: 'Projekt budowlany', description: 'Pełna dokumentacja z branżami. Formalności urzędowe prowadzimy w Waszym imieniu.' } ],
	[ 'grid/process-step', { n: '04', title: 'Projekt wykonawczy', description: 'Detale, zestawienia, dokumentacja przetargowa dla wykonawców.' } ],
	[ 'grid/process-step', { n: '05', title: 'Nadzór autorski', description: 'Wizyty na budowie w umówionym rytmie, do odbioru budynku.' } ],
];

export default function Edit( { attributes, setAttributes } ) {
	const { label, note } = attributes;
	const blockProps = useBlockProps();
	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'mt-7 grid grid-cols-1 gap-7 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 lg:gap-7' },
		{
			template: TEMPLATE,
			allowedBlocks: [ 'grid/process-step' ],
			renderAppender: InnerBlocks.ButtonBlockAppender,
		}
	);

	return (
		<div { ...blockProps }>
			<div className="flex flex-col gap-2 border-b-2 border-divider pb-3.5 md:flex-row md:items-baseline md:justify-between">
				<RichText
					tagName="h2"
					className="grid-process-steps__label m-0 text-[26px] font-extrabold uppercase tracking-[-0.035em] sm:text-[30px] md:text-[34px]"
					value={ label }
					onChange={ ( label ) => setAttributes( { label } ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="p"
					className="grid-process-steps__note m-0 max-w-[440px] text-[13px] text-ink/60 md:text-right"
					value={ note }
					onChange={ ( note ) => setAttributes( { note } ) }
					placeholder="Krótka notatka wyrównana do prawej na desktopie…"
				/>
			</div>
			<div { ...innerBlocksProps } />
		</div>
	);
}
