<?php

// Base URL of the website, without trailing slash.
$base_url = 'https://notes.orga.cat';

// Directory to save user documents.
$data_directory = '_tmp';

/**
 * Sanitizes a string to include only alphanumeric characters.
 *
 * @param  string $string the string to sanitize
 * @return string         the sanitized string
 */
function sanitizeString($string) {
    return preg_replace('/[^a-zA-Z0-9]+/', '', $string);
}

// Disable caching.
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

if (empty($_GET['f']) || sanitizeString($_GET['f']) !== $_GET['f']) {

    // User has not specified a valid name, generate one.
    $name_length = 5;

    // Initially based on http://stackoverflow.com/a/4356295/1391963
    // Do not generate ambiguous characters. See http://ux.stackexchange.com/a/53345/25513
    $characters = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
    $random_string = '';
    for ($i = 0; $i < $name_length; ++$i) {
        $random_string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    // Redirect user to a new note.
    header("Location: $base_url/$random_string");
    die();
}

$name = sanitizeString($_GET['f']);
$path = $data_directory . DIRECTORY_SEPARATOR . $name;

if (isset($_POST['t'])) {

    // If input is not empty (using empty() would not work for some falsy values)
    if (strlen($_POST['t'])) {

        // Update file.
        file_put_contents($path, $_POST['t']);
    }
    else {

        // There is no need to keep an empty file, remove it.
        unlink($path);
    }
    die();
}

if (strpos($_SERVER['HTTP_USER_AGENT'], 'curl') === 0) {

    // Output raw file if client is curl.
    if (is_file($path)) {
        print file_get_contents($path);
    }
    die();
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="generator" content="Minimalist Web Notepad (https://github.com/pereorga/minimalist-web-notepad)" />
    <title><?php print $name; ?></title>
    <link rel="shortcut icon" href="<?php print $base_url; ?>/favicon.ico" />
    <link href="<?php print $base_url; ?>/styles.css" rel="stylesheet" />
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
