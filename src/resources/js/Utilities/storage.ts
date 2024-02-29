/**
 * セッションストレージにデータをBase64エンコードして保存
 */
export const setSessionStorageBase64 = (key: string, value: string): void => {
  const data = new TextEncoder().encode(value);
  const base64Value = btoa(String.fromCharCode(...data));
  window.sessionStorage.setItem(key, base64Value);
};
/**
 * セッションストレージからBase64エンコードされたデータを取得してデコード
 */
export const getSessionStorageBase64 = (key: string): string | null => {
  const base64Value = window.sessionStorage.getItem(key);
  if (base64Value) {
    const bytes = Uint8Array.from(atob(base64Value), (c) => c.charCodeAt(0));
    const decoder = new TextDecoder();
    const decodedValue = decoder.decode(bytes);
    return decodedValue;
  }
  return null;
};

/**
 * セッションストレージのデータを削除
 */
export const deleteSessionStorage = (key: string): void => {
  window.sessionStorage.removeItem(key);
};
