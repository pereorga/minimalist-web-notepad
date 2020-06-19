/*! Minimalist Web Notepad | https://github.com/pereorga/minimalist-web-notepad */

async function uploadContent() {

    // If textarea value changes.
    if (content !== textarea.value) {
        var temp = textarea.value;

        // Make the content available to print.
        printable.removeChild(printable.firstChild);
        printable.appendChild(document.createTextNode(temp));

        var request = new XMLHttpRequest();
        request.open('POST', window.location.href, true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        request.onload = function() {
            if (request.readyState === 4) {

                // Request has ended, check again after 1 second.
                content = temp;
                setTimeout(uploadContent, 1000);
            }
        }
        request.onerror = function() {

            // Try again after 1 second.
            setTimeout(uploadContent, 1000);
        }
        request.send('text=' + encodeURIComponent(CRYPT_ENABLED ? await encrypt(temp, key) : temp));
    }
    else {

        // Content has not changed, check again after 1 second.
        setTimeout(uploadContent, 1000);
    }
}


// Run when DOM is loaded
let content, key, salt, printable, textarea;
window.onload = async function() {
    textarea = document.getElementById('content');
    printable = document.getElementById('printable');

    // Setup encryption
    if (CRYPT_ENABLED) {
        salt = new TextEncoder().encode(SALT);
        if (!window.location.hash) {
            window.location.hash = await generatePassword(16);
        }
        key = await deriveKey(window.location.hash.substr(1), salt);
        if (textarea.value) {
            try {
                textarea.value = await decrypt(textarea.value, key);
            } catch(e) {
                textarea.value = 'Unable to decrypt note. Please append the correct password to the URL "#<password>" and reload.';
            }
        }
    }
    content = textarea.value;

    // Make the content available to print.
    printable.appendChild(document.createTextNode(content));

    textarea.focus();
    uploadContent();
}
