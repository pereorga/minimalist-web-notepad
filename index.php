<?php

// Base URL of the website, without trailing slash.
$base_url = 'https://notes.orga.cat';

// Disable caching.
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// If a note's name is not provided or contains non-alphanumeric/non-ASCII characters.
if (!isset($_GET['note']) || !preg_match('/^[a-zA-Z0-9]+$/', $_GET['note'])) {

    // Generate a name with 5 random unambiguous characters. Redirect to it.
    header("Location: $base_url/" . substr(str_shuffle('234579abcdefghjkmnpqrstwxyz'), -5));
    die;
}

$path = '_tmp/' . $_GET['note'];

if (isset($_POST['text'])) {

    // Update file.
    file_put_contents($path, $_POST['text']);

    // If provided input is empty, delete file.
    if (!strlen($_POST['text'])) {
        unlink($path);
    }
    die;
}

// Output raw file if client is curl.
if (strpos($_SERVER['HTTP_USER_AGENT'], 'curl') === 0) {
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
    </div>
    <pre id="printable"></pre>
    <script src="<?php print $base_url; ?>/script.js"></script>
</body>
</html>
