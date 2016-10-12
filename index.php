<?php

// Base URL of the website, without trailing slash.
$base_url = 'https://orga.cat/notes';

// Directory to save user content.
$data_directory = '_tmp';


function sanitizeString($string) {
    return preg_replace("/[^a-zA-Z0-9]+/", "", $string);
}

function generateRandomString($length = 5) {
    // Borrowed from http://stackoverflow.com/a/4356295/1391963
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

if (empty($_GET['f'])) {
    // User has not specified a name, get one and refresh.
    header('Location: ' . $base_url . '/' . generateRandomString());
    die();
}

$name = sanitizeString($_GET['f']);
$path = $data_directory . '/' . $name;

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
    <link href="styles.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <textarea id="content"><?php
            if (file_exists($path)) {
                print htmlspecialchars(file_get_contents($path), ENT_QUOTES, 'UTF-8');
            }
?></textarea>
    </div>
    <script src="script.js"></script>
</body>
</html>
