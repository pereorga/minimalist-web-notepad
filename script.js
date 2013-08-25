$(function() {
    var $textarea = $(".content");
    var content = $textarea.val();

    // Use jQuery Tabby Plugin to enable the tab key on textareas.
    $textarea.tabby();

    // Make content available to print.
    $(".print").text(content);

    $textarea.focus();

    // If content changes, update it.
    setInterval(function() {
        if (content !== $textarea.val()) {
            content = $textarea.val();
            $.ajax({
                type: "POST",
                data: "&t=" + encodeURIComponent(content)
            });
            $(".print").text(content);
        }
    }, 1000);
});
