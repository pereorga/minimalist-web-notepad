<?php

// Base URL of the website, without trailing slash.
$base_url = 'https://notes.orga.cat';

// Directory to save notes.
$data_directory = '_tmp';

// Disable caching.
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// If provided file name is empty or has non-alphanumeric (ASCII) characters, discard it.
if (!isset($_GET['note']) || !preg_match('/^[a-z0-9]+$/i', $_GET['note'])) {

    // Generate a random note name.
    $name_length = 5;

    // Initially based on http://stackoverflow.com/a/4356295/1391963
    // Do not generate ambiguous characters. See http://ux.stackexchange.com/a/53345/25513
    $characters = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
    $random_string = '';
    for ($i = 0; $i < $name_length; ++$i) {
        $random_string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    // Redirect user to the new note.
    header("Location: $base_url/$random_string");
    die;
}

$path = "$data_directory/" . $_GET['note'];

if (isset($_POST['text'])) {

    // If input is not empty.
    if (strlen($_POST['text'])) {

        // Update file.
        file_put_contents($path, $_POST['text']);
    }
    else {

        // There is no need to keep an empty file, remove it.
        unlink($path);
    }
    die;
}

if (strpos($_SERVER['HTTP_USER_AGENT'], 'curl') === 0) {

    // Output raw file if client is curl.
    if (is_file($path)) {
        print file_get_contents($path);
    }
    die;
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Minimalist Web Notepad (https://github.com/pereorga/minimalist-web-notepad)">
    <title><?php print $_GET['note']; ?></title>
    <link rel="shortcut icon" href="<?php print $base_url; ?>/favicon.ico">
    <link rel="stylesheet" href="<?php print $base_url; ?>/styles.css">
</head>
<body>
    <div class="container">
        <textarea id="content"><?php
            if (is_file($path)) {
                print htmlspecialchars(file_get_contents($path), ENT_QUOTES, 'UTF-8');
            }
        ?></textarea>
        <pre id="printable"></pre>
    </div>
    <script src="<?php print $base_url; ?>/script.js"></script>
</body>
</html>
