/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { ButtonGroup, Button } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import hasClass from '@swell-guten/utils/hasClass';
import setClass from '@swell-guten/utils/setClass';

/**
 * @Others dependencies
 */
// import classnames from 'classnames';

/**
 * BoxStyleControls
 */
export default ({ attributes, setAttributes, styles }) => {
	const nowClass = attributes.className || '';

	// クラス名だけの配列をまずは生成しておく
	const classArray = [];
	Object.keys(styles).forEach((key) => {
		classArray.push('is-style-' + key);
	});

	// アップデートにより廃止されたクラス名
	classArray.push('is-style-note_box');
	classArray.push('is-style-border_sg');
	classArray.push('is-style-border_dg');
	classArray.push('is-style-border_sm');
	classArray.push('is-style-border_dm');
	classArray.push('is-style-bg_main');
	classArray.push('is-style-bg_main_thin');
	classArray.push('is-style-bg_gray');

	classArray.push('is-style-big_icon_good');
	classArray.push('is-style-big_icon_bad');

	const clearButton = (
		<div className='__clearBtn'>
			<Button
				isSmall
				onClick={() => {
					const newClass = setClass(nowClass, '', classArray);
					setAttributes({ className: newClass });
				}}
			>
				{__('スタイルをクリア')}
			</Button>
		</div>
	);

	return (
		<ButtonGroup className='swl-style-ctrls'>
			{clearButton}
			{Object.keys(styles).map((key) => {
				const styleClass = 'is-style-' + key;
				const label = styles[key];
				let isSelected = false;

				if (hasClass(nowClass, styleClass)) {
					isSelected = true;
				} else if (
					'is-style-big_icon_check' === styleClass &&
					hasClass(nowClass, 'is-style-big_icon_good')
				) {
					isSelected = true;
				} else if (
					'is-style-big_icon_batsu' === styleClass &&
					hasClass(nowClass, 'is-style-big_icon_bad')
				) {
					isSelected = true;
				}
				return (
					<div className='__btnBox' key={`key_style_${key}`}>
						<button
							type='button'
							id={'swell_style_' + key}
							className='__btn u-none'
							onClick={() => {
								const selectedClass = isSelected ? '' : styleClass;
								const newClass = setClass(nowClass, selectedClass, classArray);

								setAttributes({ className: newClass });
							}}
						></button>
						<label
							htmlFor={'swell_style_' + key}
							className='__labelBtn'
							data-selected={isSelected}
						>
							<span className='__prevWrap -style'>
								<span className={'__prev ' + styleClass} />
							</span>
							<span className='__prevTitle'>{label}</span>
						</label>
					</div>
				);
			})}
			{clearButton}
		</ButtonGroup>
	);
};
