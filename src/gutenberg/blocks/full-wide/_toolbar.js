/**
 * @WordPress dependencies
 */
import { useMemo } from '@wordpress/element';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { ToolbarGroup, ToolbarButton } from '@wordpress/components';

/**
 * @Self dependencies
 */
import blockIcon from './_icon';

export default ({ attributes, setAttributes }) => {
	const { contentSize, bgImageID, bgImageUrl, bgOpacity } = attributes;

	const toolbarControls = useMemo(
		() => [
			{
				icon: blockIcon.article,
				title: 'コンテンツ幅：記事と同じ',
				isActive: 'article' === contentSize,
				onClick: () => setAttributes({ contentSize: 'article' }),
			},
			{
				icon: blockIcon.container,
				title: 'コンテンツ幅：サイト幅',
				isActive: 'container' === contentSize,
				onClick: () => setAttributes({ contentSize: 'container' }),
			},
			{
				icon: blockIcon.full,
				title: 'コンテンツ幅：フルワイド',
				isActive: 'full' === contentSize,
				onClick: () => setAttributes({ contentSize: 'full' }),
			},
		],
		[contentSize, setAttributes]
	);
	return (
		<>
			<ToolbarGroup controls={toolbarControls} />
			<MediaUploadCheck>
				<ToolbarGroup>
					<MediaUpload
						onSelect={(media) => {
							// 画像がなければ
							if (!media || !media.url) {
								setAttributes({
									bgImageUrl: '',
									bgImageID: 0,
									bgOpacity: 100,
								});
								return;
							}
							setAttributes({
								bgImageUrl: media.url,
								bgImageID: media.id,
								...(100 === bgOpacity ? { bgOpacity: 50 } : {}),
							});
						}}
						allowedTypes={'image'}
						value={bgImageID}
						render={({ open }) => (
							<ToolbarButton
								className='components-toolbar__control'
								label='背景画像を選択'
								icon='edit'
								onClick={open}
							/>
						)}
					/>
				</ToolbarGroup>
				{bgImageUrl && (
					<ToolbarGroup>
						<ToolbarButton
							className='components-toolbar__control'
							label='背景画像を削除'
							icon='no-alt'
							onClick={() => {
								setAttributes({
									bgImageUrl: '',
									bgImageID: 0,
									bgOpacity: 100,
								});
							}}
						/>
					</ToolbarGroup>
				)}
			</MediaUploadCheck>
		</>
	);
};
