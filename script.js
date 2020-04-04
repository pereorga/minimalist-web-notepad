/*! Minimalist Web Notepad | https://github.com/pereorga/minimalist-web-notepad */

function uploadContent() {

    // If textarea value changes.
    if (content !== textarea.value) {
        var temp = textarea.value;
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
        request.send('text=' + encodeURIComponent(temp));

        // Make the content available to print.
        printable.removeChild(printable.firstChild);
        printable.appendChild(document.createTextNode(temp));
    }
    else {

        // Content has not changed, check again after 1 second.
        setTimeout(uploadContent, 1000);
    }
}

var textarea = document.getElementById('content');
var printable = document.getElementById('printable');
var content = textarea.value;

// Make the content available to print.
printable.appendChild(document.createTextNode(content));

// Create tab spaces on 'Tab' key. 
// From https://stackoverflow.com/questions/6637341/use-tab-to-indent-in-textarea
textarea.onkeydown = function(e) {
	tab_space=2;
	if(e.keyCode===9){
		var v=this.value, s=this.selectionStart, end=this.selectionEnd;
		this.value=v.substring(0, s) + '  ' + v.substring(end);
		this.selectionStart=this.selectionEnd=s+tab_space;
		return false;
	}
}

textarea.focus();
uploadContent();
