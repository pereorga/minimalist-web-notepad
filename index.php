<?php

// Base URL of the website, without trailing slash.
$base_url = 'https://orga.cat/notes';

// Directory to save user documents.
$data_directory = '_tmp';

/**
 * Sanitizes a string to include only alphanumeric characters.
 *
 * @param  string $string the string to sanitize
 * @return string         the sanitized string
 */
function sanitizeString($string) {
    return preg_replace("/[^a-zA-Z0-9]+/", "", $string);
}

/**
 * Generates a random string.
 *
 * @param  integer $length the length of the string
 * @return string          the new string
 *
 * Initiall based on http://stackoverflow.com/a/4356295/1391963
 */
function generateRandomString($length = 5) {
    // Do not generate ambiguous characters. See http://ux.stackexchange.com/a/53345/25513
    $characters = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


// Disable caching.
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (empty($_GET['f']) || sanitizeString($_GET['f']) !== $_GET['f']) {

    // User has not specified a valid name, generate one.
    header('Location: ' . $base_url . '/' . generateRandomString());
    die();
}

$name = sanitizeString($_GET['f']);
$path = $data_directory . '/' . $name;

if (isset($_POST['t'])) {

    // Update file.
    file_put_contents($path, $_POST['t']);
    die();
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php print $name; ?></title>
    <link rel="shortcut icon" href="<?php print $base_url; ?>/favicon.ico" />
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
