<?php

// Base URL of the website, without trailing slash.
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
$base_url = $protocol . $_SERVER['HTTP_HOST'];


// Path to the directory to save the notes in, without trailing slash.
// Should be outside of the document root, if possible.
$save_path = getenv('MWN_SAVE_PATH') ?: '_tmp';

// Salt for encryption
$encryption = getenv('MWN_ENCRYPTION') ?: false;
$salt = getenv('MWN_CRYPTO_SALT') ?: 'c36g0bZfykx254eyzQNZ8SNR0gT78D89';

// Disable caching.
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// If no name is provided or it contains invalid characters or it is too long.
if (!isset($_GET['note']) || !preg_match('/^[a-zA-Z0-9_-]+$/', $_GET['note']) || strlen($_GET['note']) > 64) {

    // Generate a name with 5 random unambiguous characters. Redirect to it.
    header("Location: $base_url/" . substr(str_shuffle(str_repeat('23456789abcdefghjkmnpqrstwxyz', 5)), -5));
    die;
}

$path = $save_path . '/' . $_GET['note'];

if (isset($_POST['text'])) {

    // Update file.
    file_put_contents($path, $_POST['text']);

    // If provided input is empty, delete file.
    if (!strlen($_POST['text'])) {
        unlink($path);
    }
    die;
}

// Print raw file if the client is curl, wget, or when explicitly requested.
if (isset($_GET['raw']) || strpos($_SERVER['HTTP_USER_AGENT'], 'curl') === 0 || strpos($_SERVER['HTTP_USER_AGENT'], 'Wget') === 0) {
    if (is_file($path)) {
        header('Content-type: text/plain');
        print file_get_contents($path);
    } else {
        header('HTTP/1.0 404 Not Found');
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
    <script type="text/javascript">
        const ipReg = /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/
        const CRYPT_ENABLED = ipReg.test(window.location.host) && "127.0.0.1" !== window.location.host ? false : <?php print $encryption ? "true" : "false"; ?>;
    </script>
    <?php if ($encryption) : ?>
        <script type="text/javascript">const SALT = "<?php print $salt; ?>";</script>
        <script src="<?php print $base_url; ?>/crypt.js"></script>
    <?php endif; ?>
    <script src="<?php print $base_url; ?>/script.js"></script>
</body>
</html>
