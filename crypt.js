const unambiguousChars = '23456789abcdefghjkmnpqrstwxyz';  // easily human-readable
async function generatePassword(length) {
  let password = window.crypto.getRandomValues(new Uint32Array(length));
  return password.reduce((str,chr)=>str+unambiguousChars[chr%unambiguousChars.length],'');  // chars from unambiguousChars list
}
async function deriveKey(password, salt) {
  let keyMaterial = await window.crypto.subtle.importKey("raw", new TextEncoder().encode(password), "PBKDF2", false, ["deriveBits", "deriveKey"]);
  return window.crypto.subtle.deriveKey(
    { name: "PBKDF2", salt: salt, iterations: 100000, hash: "SHA-256" },
    keyMaterial,
    { name: "AES-GCM", length: 256},
    true,
    [ "encrypt", "decrypt" ]
  );
}
const IV_LENGTH = 12;  /// 96 bits
async function encrypt(plaintext, key) {
  let iv = window.crypto.getRandomValues(new Uint8Array(IV_LENGTH));
  let ciphertext = await window.crypto.subtle.encrypt({ name: "AES-GCM", iv: iv }, key, new TextEncoder().encode(plaintext));
  return btoa(String.fromCharCode(...iv,...new Uint8Array(ciphertext)));  // base64
}
async function decrypt(bufferEncoded, key) {
  let buffer = Uint8Array.from(atob(bufferEncoded), c => c.charCodeAt(0))  //base64
  return new TextDecoder().decode(await window.crypto.subtle.decrypt({ name: "AES-GCM", iv: buffer.subarray(0, IV_LENGTH) }, key, buffer.subarray(IV_LENGTH)));
}
