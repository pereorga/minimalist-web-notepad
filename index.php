<?php

// Base URL of the website, without trailing slash.
$BASE_URL = 'https://orga.cat/notes';

// Directory to save user content.
$DATA_DIRECTORY = '_tmp';


function sanitize_file_name($filename) {
    // Original function borrowed from Wordpress.
    $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", ".");
    $filename = str_replace($special_chars, '', $filename);
    $filename = preg_replace('/[\s-]+/', '-', $filename);
    $filename = trim($filename, '.-_');
    return $filename;
}

function generateRandomString($length = 5) {
    // Borrowed from http://stackoverflow.com/a/4356295/1391963
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (empty($_GET['f'])) {
    // User has not specified a name, get one and refresh.
    header('Location: ' . $BASE_URL . '/' . generateRandomString());
    die();
}

$name = sanitize_file_name($_GET['f']);
$path = $DATA_DIRECTORY . '/' . $name;

if (isset($_POST['t'])) {
    // Update content of file
    file_put_contents($path, $_POST['t']);
    die();
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php print $name; ?></title>
    <link href="lib/normalize.css" rel="stylesheet" />
    <link href="styles.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <textarea class="content" spellcheck="true"><?php 
            if (file_exists($path)) {
                print htmlspecialchars(file_get_contents($path));
            }
?></textarea>
    </div>
    <pre class="print"></pre>
    <script src="//code.jquery.com/jquery.min.js"></script>
    <script src="lib/jquery.textarea.js"></script>
    <script src="script.js"></script>
</body>
</html>
