<?php

$WEB_URL = "http://orga.cat/notes";

//original function source code from wordpress
function sanitize_file_name($filename) {
    $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", ".");
    $filename = str_replace($special_chars, '', $filename);
    $filename = preg_replace('/[\s-]+/', '-', $filename);
    $filename = trim($filename, '.-_');
    return $filename;
}


if (!isset($_GET["f"])) {
    $lines = file("words.txt");
    $fit = trim($lines[array_rand($lines)], "\n");
    while (file_exists("_tmp/".$fit) && strlen($fit) < 10) {
        $fit .= rand(0,9);
    }
    if (strlen($fit) < 10) {
        header("Location: ".$WEB_URL."/".$fit);
    }
    die();
}
else {
    $fit = sanitize_file_name($_GET["f"]);
}

$pfit = "_tmp/" . $fit;

if (isset($_POST["t"])) {
    file_put_contents($pfit, $_POST["t"]);
    die();
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Pere Orga" />
    <title><?php print $fit; ?></title>
<<<<<<< HEAD
    <link href="//raw.github.com/necolas/normalize.css/master/normalize.css" rel="stylesheet" media="all" />
=======
>>>>>>> 6c1c54f61a6b5c6c9cf0122924d043bf3b0d833b
    <link href="screen.css" rel="stylesheet" media="screen" />
    <link href="print.css" rel="stylesheet" media="print" />
    <link href="favicon.gif" rel="shortcut icon" />
</head>
<body>
    <div>
        <textarea id="ta" spellcheck="true"><?php 
            if (file_exists($pfit)) {
                print htmlspecialchars(file_get_contents($pfit));
            }
?></textarea>
    </div>
    <pre id="print"></pre>
<<<<<<< HEAD
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//teddevito.com/demos/js/jquery.textarea.js"></script>
    <script src="notes.js"></script>
</body>
</html>
=======
    <script src="//code.jquery.com/jquery-1.3.2.min.js"></script>
    <script src="//teddevito.com/demos/js/jquery.textarea.js"></script>
    <script src="notes.js"></script>
</body>
</html>
>>>>>>> 6c1c54f61a6b5c6c9cf0122924d043bf3b0d833b
