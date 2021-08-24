/**
 * カラム数から各カラムの幅を取得する関数
 */
export const getColumnBasis = (colmunNum) => {
	const percent = Math.floor(10000 / colmunNum) / 100;
	return percent + '%';
};
