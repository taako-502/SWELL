/**
 * @WordPress dependencies
 */
import {
	MediaUpload,
	MediaUploadCheck,
	AlignmentToolbar,
	BlockVerticalAlignmentToolbar,
} from '@wordpress/block-editor';
import { ToolbarButton, ToolbarGroup } from '@wordpress/components';

export default ({ imageID, imageUrl, alignment, verticalAlignment, setAttributes }) => {
	return (
		<>
			<AlignmentToolbar
				value={alignment}
				onChange={(value) => setAttributes({ alignment: value })}
			/>
			<BlockVerticalAlignmentToolbar
				onChange={(value) => setAttributes({ verticalAlignment: value })}
				value={verticalAlignment}
			/>
			<MediaUploadCheck>
				<ToolbarGroup>
					<MediaUpload
						onSelect={(media) => {
							if (!media || !media.url) {
								// 画像がなければ
								setAttributes({
									imageUrl: '',
									imageID: 0,
									imageAlt: '',
								});
							} else {
								// 画像があれば
								setAttributes({
									imageUrl: media.url,
									imageID: media.id,
									imageAlt: media.alt,
								});
							}
						}}
						allowedTypes={'image'}
						value={imageID}
						render={({ open }) => (
							<ToolbarButton
								className='components-toolbar__control'
								label='背景画像を選択'
								icon='edit'
								onClick={open}
							/>
						)}
					/>
					{imageUrl && (
						<ToolbarButton
							className='components-toolbar__control'
							label='背景画像を削除'
							icon='no-alt'
							onClick={() => {
								setAttributes({
									imageUrl: '',
									imageID: 0,
									imageAlt: '',
								});
							}}
						/>
					)}
				</ToolbarGroup>
			</MediaUploadCheck>
		</>
	);
};
