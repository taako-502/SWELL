/**
 * @WordPress dependencies
 */
import { registerFormatType } from '@wordpress/rich-text';

/**
 * @Self dependencies
 */
import { clear } from './clear'; // 書式クリア
import { codeDir } from './code-dir'; // フォルダアインコン付きコード
import { codeFile } from './code-file'; // ファイルアインコン付きコード
import { bgColor } from './bg-color'; // 背景色
import { marker } from './marker'; // マーカー
import { fontSize } from './font-size'; // フォントサイズ
import { note } from './mini-note'; // 注釈ボタン
import { nowrap } from './nowrap'; // nowrapボタン
import { customFormats } from './custom'; // カスタムボタン

const formats = [
	clear,
	codeDir,
	codeFile,
	bgColor,
	marker,
	fontSize,
	note,
	nowrap,
	...customFormats,
];
formats.forEach(({ name, ...settings }) => registerFormatType(name, settings));
