// 配列の要素を移動させる
const moveAt = (array, index, at) => {
	// 移動下と移動先が同じ場合や、どちらかが配列の長さを超える場合は return
	if (index === at || index > array.length - 1 || at > array.length - 1) {
		return array;
	}

	const value = array[index];
	const tail = array.slice(index + 1);

	array.splice(index);

	Array.prototype.push.apply(array, tail);

	array.splice(at, 0, value);

	return array;
};

export default moveAt;
