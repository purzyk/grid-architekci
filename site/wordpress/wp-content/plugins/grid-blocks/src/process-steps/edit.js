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
	const blockProps = useBlockProps( { className: 'grid-process-steps' } );
	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'grid-process-steps__grid' },
		{
			template: TEMPLATE,
			allowedBlocks: [ 'grid/process-step' ],
			renderAppender: InnerBlocks.ButtonBlockAppender,
		}
	);

	return (
		<div { ...blockProps }>
			<div className="grid-process-steps__head">
				<RichText
					tagName="h2"
					className="grid-process-steps__label"
					value={ label }
					onChange={ ( label ) => setAttributes( { label } ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="p"
					className="grid-process-steps__note"
					value={ note }
					onChange={ ( note ) => setAttributes( { note } ) }
					placeholder="Krótka notatka wyrównana do prawej na desktopie…"
				/>
			</div>
			<div { ...innerBlocksProps } />
		</div>
	);
}
