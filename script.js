/* Pere Orga <pere@orga.cat>, 2012 */

$(function() {
    var $textarea = $("#content");
    var content = $textarea.val();

    // Use jQuery Tabby Plugin to enable the tab key on textareas
    $textarea.tabby();

    // Make the content available to print
    $("#print").text(content);

    $textarea.focus();

    // If the content changes, update it every second
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
