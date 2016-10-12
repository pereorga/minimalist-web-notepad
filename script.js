var textarea = document.getElementById('content');
var content = textarea.value;

textarea.focus();

setInterval(function() {
    if (content !== textarea.value) {

        content = textarea.value;
        var request = new XMLHttpRequest();
        request.open('POST', window.location.href, true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        request.send("t=" + encodeURIComponent(content));
    }
}, 1000);
