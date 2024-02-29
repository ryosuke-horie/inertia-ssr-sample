export function getNameByEnumValue<T extends { value: number; name: string }>(
  enumObj: { [s: string]: T },
  value: number,
): string | undefined {
  const entries = Object.entries(enumObj);
  for (const [, enumValue] of entries) {
    if (enumValue.value === value) {
      return enumValue.name;
    }
  }
  return undefined;
}

export function formatSelectBoxOptions(
  optionsData: { value: string | number; label: string }[], // オプションデータの型直接指定
  defaultValue: string | number = '', // デフォルトのvalue
  defaultLabel: string = '選択してください', // デフォルトのラベル
): { value: string | number; label: string }[] {
  // 返り値の型も直接指定
  // デフォルトオプション
  const hiddenOptions = [{ value: defaultValue, label: defaultLabel }];

  // 渡されたオプションデータのフォーマット（必要に応じてさらに変換処理を追加）
  const formattedData = optionsData.map((option) => ({
    value: option.value,
    label: option.label,
  }));

  // デフォルトオプションと変換したオプションを結合
  return [...hiddenOptions, ...formattedData];
}
