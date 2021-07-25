/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { memo, useMemo, useState } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import { URLInput, InspectorControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { PanelBody, ToggleControl, TextControl } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import { iconColor } from '@swell-guten/config';
import example from './_example';
import exampleHtml from './_exampleHtml';
import deprecated from './deprecated';
import setBlankRel from '@swell-guten/utils/setBlankRel';
import SwellTab from '@swell-guten/components/SwellTab';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/* eslint jsx-a11y/no-autofocus: 0 */

const SettingArea = memo(
	({ postId, postTitle, externalUrl, isSelected, setAttributes, tabKey }) => {
		return (
			<div className='swell-block-postLink__inputWrapper'>
				{'internal' === tabKey && (
					<div className='__internalArea'>
						<input
							type='text'
							className='__idInput'
							value={postId}
							placeholder='IDを入力'
							onChange={(e) => {
								e.preventDefault();
								setAttributes({
									postId: e.target.value,
									externalUrl: '',
								});
							}}
						/>
						<URLInput
							value={postTitle}
							className='__urlInput'
							autoFocus={false}
							hasBorder
							placeholder='タイトルを入力して記事を検索'
							disableSuggestions={!isSelected}
							onChange={(url, post) => {
								if (post) {
									const title = post.title.replace('&#8211;', '-');
									setAttributes({
										postId: post.id + '',
										postTitle: title,
										externalUrl: '',
									});
								} else {
									setAttributes({
										postTitle: url,
										postId: '',
										externalUrl: '',
									});
								}
							}}
						/>
					</div>
				)}
				{'external' === tabKey && (
					<div className='__externalArea'>
						<TextControl
							// label='外部リンクのURL'
							className='__externalInput'
							placeholder='URLを直接入力してください。'
							value={externalUrl}
							onChange={(val) => {
								setAttributes({
									externalUrl: val,
									postId: '',
									postTitle: '',
									isNewTab: false,
								}); //無駄にattribute持たせない
							}}
						/>
					</div>
				)}
			</div>
		);
	}
);

/**
 * 関連記事ブロック
 */
registerBlockType('loos/post-link', {
	title: '関連記事',
	description: __('関連記事をブログカード型で表示します。', 'swell'),
	icon: {
		foreground: iconColor,
		src: 'admin-links',
	},
	category: 'swell-blocks',
	keywords: ['swell', 'blogcard', 'postlink'],
	supports: {
		className: false,
	},
	example,
	attributes: {
		postTitle: {
			type: 'string',
			default: '',
		},
		postId: {
			type: 'string',
			default: '',
		},
		cardCaption: {
			type: 'string',
			default: '',
		},
		isNewTab: {
			type: 'boolean',
			default: false,
		},
		externalUrl: {
			type: 'string',
			default: '',
		},
		isPreview: {
			type: 'boolean',
			default: false,
		},
		rel: {
			type: 'string',
			default: '',
		},
		hideImage: {
			type: 'boolean',
			default: false,
		},
		hideExcerpt: {
			type: 'boolean',
			default: false,
		},
		isText: {
			type: 'boolean',
			default: false,
		},
	},

	edit: ({ attributes, setAttributes, className, isSelected }) => {
		const {
			isPreview,
			postId,
			postTitle,
			externalUrl,
			cardCaption,
			isNewTab,
			rel,
			hideImage,
			hideExcerpt,
			isText,
			// isCached,
		} = attributes;

		// プレビュー時
		if (isPreview) return exampleHtml;

		// タブ用のステート
		const isExternal = !!externalUrl;
		const defaultTab = isExternal ? 'external' : 'internal';
		const [tabKey, setTabKey] = useState(defaultTab);

		// relの値
		const relVal = useMemo(() => {
			let _relVal = rel;
			if (externalUrl && !rel) _relVal = 'noopener noreferrer';
			if (isNewTab && !rel) _relVal = 'noopener noreferrer';
			return _relVal;
		}, [rel, externalUrl, isNewTab]);

		// 現在の記事のID
		// const currentID = useSelect((select) => select('core/editor').getCurrentPostId());

		return (
			<>
				<InspectorControls>
					<PanelBody title='設定' initialOpen={true}>
						<ToggleControl
							className='u-mb-10'
							label={__('テキストリンクで表示する', 'swell')}
							checked={isText}
							onChange={(value) => {
								setAttributes({ isText: value });
							}}
						/>
						<div data-swl-disable={isText || null}>
							<ToggleControl
								className='u-mb-10'
								label={__('画像を非表示にする', 'swell')}
								checked={hideImage}
								onChange={(value) => {
									setAttributes({ hideImage: value });
								}}
							/>
							<ToggleControl
								className='u-mb-10'
								label={__('抜粋文を非表示にする', 'swell')}
								checked={hideExcerpt}
								onChange={(value) => {
									setAttributes({ hideExcerpt: value });
								}}
							/>
							<TextControl
								label='キャプション'
								value={cardCaption}
								onChange={(val) => {
									setAttributes({ cardCaption: val });
								}}
							/>
						</div>
					</PanelBody>
					<PanelBody title='リンク設定' initialOpen={true}>
						<ToggleControl
							id='loosbtn_is_new_open'
							label={__('Open in new tab')}
							help='※ 外部サイトへのリンクでは、「新しいタブで開く」の設定は無視され、強制的にオンになります。'
							checked={externalUrl ? true : isNewTab}
							className={externalUrl ? '-is-external' : null}
							onChange={(value) => {
								const newRel = setBlankRel(value, rel);
								setAttributes({
									isNewTab: value,
									rel: newRel,
								});
							}}
						/>
						<TextControl
							label={__('Link rel')}
							value={relVal || ''}
							onChange={(value) => {
								setAttributes({ rel: value });
							}}
						/>
					</PanelBody>
				</InspectorControls>
				<div className={classnames('swell-block-postLink', className)}>
					<div className='swell-block-postLink__preview'>
						{postId || externalUrl ? (
							<ServerSideRender block='loos/post-link' attributes={attributes} />
						) : (
							<div className='swell-block-postLink__none'>
								{'internal' === tabKey
									? '※ ブログカードとして表示したい投稿を指定してください。'
									: '※ ブログカードとして表示したいURLを入力してください。'}
							</div>
						)}
					</div>
					{isSelected && (
						<>
							<SwellTab
								className='-post-link'
								tabs={[
									{ key: 'internal', label: '内部リンク' },
									{ key: 'external', label: '外部リンク' },
								]}
								state={tabKey}
								setState={setTabKey}
							></SwellTab>
							<SettingArea
								{...{
									postId,
									postTitle,
									externalUrl,
									isSelected,
									setAttributes,
									tabKey,
								}}
							/>
						</>
					)}
				</div>
			</>
		);
	},
	save: () => {
		return null;
	},
	deprecated,
});
