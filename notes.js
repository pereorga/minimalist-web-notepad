/* Pere Orga <pere@orga.cat>, 2012 */

$(function() {
    var $ta   = $("#ta");
    var vtext = $ta.val();
    
    $ta.tabby();
    $ta.focus();
    $("#print").text(vtext);

    setInterval(function() {
        if (vtext !== $ta.val()) {
            vtext = $ta.val();
            $.ajax({
                type: "POST",
                data: "&t=" + encodeURIComponent(vtext)
            });
            $("#print").text(vtext);
        }
    }, 1000);
});