<?php

$URL = "http://orga.cat/notes";
$FOLDER = "_tmp";


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
    $name = trim($lines[array_rand($lines)], "\n");
    while (file_exists($FOLDER."/".$name) && strlen($name) < 10) {
        $name .= rand(0,9);
    }
    if (strlen($name) < 10) {
        header("Location: ".$URL."/".$name);
    }
    die();
}

$name = sanitize_file_name($_GET["f"]);
$path = $FOLDER."/".$name;

if (isset($_POST["t"])) {
    file_put_contents($path, $_POST["t"]);
    die();
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Pere Orga" />
    <title><?php print $name; ?></title>
    <link href="normalize.css" rel="stylesheet" />
    <link href="screen.css" rel="stylesheet" media="screen" />
    <link href="print.css" rel="stylesheet" media="print" />
    <link href="favicon.gif" rel="shortcut icon" />
</head>
<body>
    <div>
        <textarea id="content" spellcheck="true"><?php 
            if (file_exists($path)) {
                print htmlspecialchars(file_get_contents($path));
            }
?></textarea>
    </div>
    <pre id="print"></pre>
    <script src="//code.jquery.com/jquery.min.js"></script>
    <script src="jquery.textarea.js"></script>
    <script src="notes.js"></script>
</body>
</html>
