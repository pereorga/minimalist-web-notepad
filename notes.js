/* Pere Orga <pere@orga.cat>, 2012 */

$(function() {
    var $textarea = $("#content");
    var content = $textarea.val();
    
    $textarea.tabby();
    $textarea.focus();
    $("#print").text(content);

    setInterval(function() {
        if (content !== $textarea.val()) {
            content = $textarea.val();
            $.ajax({
                type: "POST",
                data: "&t=" + encodeURIComponent(content)
            });
            $("#print").text(content);
        }
    }, 1000);
});
