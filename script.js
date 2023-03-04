/*! Minimalist Web Notepad | https://github.com/pereorga/minimalist-web-notepad */

function uploadContent() {
    if (content !== textarea.value) {
        var temp = textarea.value;
        var request = new XMLHttpRequest();
        request.open('POST', window.location.href, true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        request.onload = function() {
            if (request.readyState === 4) {

                // If the request has ended, check again after 1 second.
                content = temp;
                setTimeout(uploadContent, 1000);
            }
        }
        request.onerror = function() {

            // Try again after 1 second.
            setTimeout(uploadContent, 1000);
        }
        request.send('text=' + encodeURIComponent(temp));

        // Update the printable contents.
        printable.removeChild(printable.firstChild);
        printable.appendChild(document.createTextNode(temp));
    }
    else {

        // If the content has not changed, check again after 1 second.
        setTimeout(uploadContent, 1000);
    }
}

var textarea = document.getElementById('content');
var printable = document.getElementById('printable');
var content = textarea.value;

// Initialize the printable contents with the initial value of the textarea.
printable.appendChild(document.createTextNode(content));

textarea.focus();
uploadContent();
