<?php

// Base URL of the website, without trailing slash.
$base_url = 'https://www.example.com';

// Path to the directory to save the notes in, without trailing slash.
// Should be outside the document root, if possible.
$save_path = '_tmp';

// Disable caching.
header('Cache-Control: no-store');

// logic used
// if has /(slash)
// // if ends in slash, consider it a full path and create a random filename
// // if not ends in slash, consider last part is the filename
// else
// if has a name, consider it the filename
//
// tests
// @/ -> new file 
// @/file1 -> open file1 
// @/folder1/ -> creates folder1 and new file
// @/folder1/file1 -> open file1 from folder1 OK
// @/folder1/folder2/ -> creates folder1/folder2 and new file
// @/folder1/folder2/file1 -> open file1 from folder1/folder2
//
//-----------------------------------------------------------------------------------------------------------------------------------------------------------
//  RUNS     |           @           |        @/file1        |       @/folder1/      |   @/folder1/file1    |  @/folder1/folder2/  | @/folder1/folder2/file1 |
//-----------------------------------------------------------------------------------------------------------------------------------------------------------
//   1       |           x           |           x           |                      |           x           |          x           |           x             |   
//-----------------------------------------------------------------------------------------------------------------------------------------------------------
//   2       |           x           |                       |                      |                       |                      |                         |
//-----------------------------------------------------------------------------------------------------------------------------------------------------------
//   3       |           x           |           x           |          x           |           x           |          x           |           x             |
//-----------------------------------------------------------------------------------------------------------------------------------------------------------



// If no note name is provided, or if the name is too long, or if it contains invalid characters.
if (!isset($_GET['note']) || strlen($_GET['note']) > 64 || !preg_match('/^[a-zA-Z0-9_\/-]+$/', $_GET['note'])) {

    // Generate a name with 5 random unambiguous characters. Redirect to it.
    header("Location: $base_url/" . substr(str_shuffle('234579abcdefghjkmnpqrstwxyz'), -5));
    die;
}

$path = $save_path . '/' . $_GET['note'];

if (preg_match('/\//', $_GET['note'])) { //folder structure
    if (preg_match('/\/$/', $_GET['note'])) { //end in slash
        $newdir = $_GET['note'];
        if (!is_dir($newdir)) {
            mkdir($newdir, 0777, true);
        }
        // Generate a name with 5 random unambiguous characters. Redirect to it.
        header("Location: $base_url/" . $_GET['note'] . substr(str_shuffle('234579abcdefghjkmnpqrstwxyz'), -5));
        die;
    }
    else{
        $newdir = (dirname($path) . '/');
        if (!is_dir($newdir)) {
            mkdir($newdir, 0777, true);
        }
    }
}

if (isset($_POST['text'])) {
    // Update file.
    file_put_contents($path, $_POST['text']);

    // If provided input is empty, delete file.
    if (!strlen($_POST['text'])) {
        unlink($path);
    }
    die;
}

// Print raw file when explicitly requested, or if the client is curl or wget.
if (isset($_GET['raw']) || strpos($_SERVER['HTTP_USER_AGENT'], 'curl') === 0 || strpos($_SERVER['HTTP_USER_AGENT'], 'Wget') === 0) {
    if (is_file($path)) {
        header('Content-type: text/plain');
        readfile($path);
    } else {
        header('HTTP/1.0 404 Not Found');
    }
    die;
}

function displayFolderTree($directory, $indentation = '') {
    $files = scandir($directory);

    // Separate directories and files into two arrays
    $dirs = [];
    $other = [];

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            if (is_dir($directory . '/' . $file)) {
                $dirs[] = $file;
            } else {
                $other[] = $file;
            }
        }
    }

    // Handle directories first
    foreach ($dirs as $dir) {
        print $indentation . '- <b>' . $dir . "</b><br />";
        displayFolderTree($directory . '/' . $dir, $indentation . '--');
    }

    // Handle files next
    foreach ($other as $file) {
        print $indentation . '- ' . $file . "<br />";
    }
}

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php print $_GET['note']; ?></title>
    <link rel="icon" href="<?php print $base_url; ?>/favicon.ico" sizes="any">
    <link rel="icon" href="<?php print $base_url; ?>/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php print $base_url; ?>/styles.css">
</head>
<body>
        <div class="left">
            <span>
                <?php
                    $rootDirectory = '/home/giral/www/dontpad/_tmp';
                    displayFolderTree($rootDirectory);
                ?>
            </span>
        </div>
        <div class="right">
            <textarea id="content"><?php if (is_file($path)) {print htmlspecialchars(file_get_contents($path), ENT_QUOTES, 'UTF-8');}?></textarea>
            <pre id="printable"></pre>
        </div>
    <script src="<?php print $base_url; ?>/script.js"></script>
</body>
</html>
