import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit( { context } ) {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<ServerSideRender block="grid/project-header" urlQueryArgs={ { post_id: context.postId } } />
		</div>
	);
}
