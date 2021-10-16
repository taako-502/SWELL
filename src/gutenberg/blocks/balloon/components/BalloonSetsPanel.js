/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { memo } from '@wordpress/element';
import { addQueryArgs } from '@wordpress/url';
import { PanelBody, ButtonGroup } from '@wordpress/components';

/**
 * ふきだしセットのセレクトボックス生成
 */
const swellBalloons = window.swellBalloons || {};

const BalloonPrev = ({ balData }) => {
	const { icon, name, align, col, shape, type, border } = balData;
	return (
		<div className={`c-balloon -bln-${align}`} data-col={col}>
			{icon && (
				<div className={`c-balloon__icon -${shape}`}>
					<img className='c-balloon__iconImg' src={icon} alt='' />
					<span className='c-balloon__iconName'>{name}</span>
				</div>
			)}
			<div className={`c-balloon__body -${type} -border-${border}`}>
				<div className='c-balloon__text'>
					<p>ふきだしテキスト</p>
					<span className='c-balloon__shapes'>
						<span className='c-balloon__before'></span>
						<span className='c-balloon__after'></span>
					</span>
				</div>
			</div>
		</div>
	);
};
/**
 * 吹き出しブロック
 */
export default memo(({ balloonID, setAttributes }) => {
	return (
		<PanelBody title='ふきだしセット' initialOpen={true}>
			<ButtonGroup className='swl-style-ctrls -balloon'>
				{Object.keys(swellBalloons).map((key) => {
					const balData = swellBalloons[key];
					const theBalID = balData.id.toString();
					const theBalTitle = balData.title;
					const isSelected = theBalID === balloonID;

					return (
						<div className='__btnBox' key={`key_style_${key}`}>
							<button
								type='button'
								id={'swell_style_' + key}
								className='__btn u-none'
								onClick={() => {
									if (isSelected || '0' === theBalID) {
										setAttributes({
											balloonID: '0',
											balloonTitle: '',
										});
									} else {
										setAttributes({
											balloonID: theBalID,
											balloonTitle: theBalTitle,
										});
									}
								}}
							></button>
							<label
								htmlFor={'swell_style_' + key}
								className='__labelBtn'
								data-selected={isSelected}
							>
								<span className='__prevWrap -style'>
									<BalloonPrev balData={balData} />
								</span>
							</label>
						</div>
					);
				})}
			</ButtonGroup>

			<div className='description u-mt-5'>
				<a
					href={addQueryArgs('admin.php', {
						page: 'swell_balloon',
					})}
					target='_blank'
					rel='noreferrer'
				>
					ふきだし管理ページ
				</a>
				から登録・編集できます。
			</div>
		</PanelBody>
	);
});
