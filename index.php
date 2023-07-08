<?php

// Base URL of the website, without trailing slash.
$base_url = 'https://notes.orga.cat';

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

if (preg_match('/\//', $_GET['note'])) {
    //folder structure
    if (preg_match('/\/$/', $_GET['note'])) {
        //end in slash
        $newdir = $_GET['note'];
        if (!is_dir($newdir)) {
            mkdir($newdir, 0777, true);
        }
        // Generate a name with 5 random unambiguous characters. Redirect to it.
        header("Location: $base_url/" . $_GET['note'] . substr(str_shuffle('234579abcdefghjkmnpqrstwxyz'), -5));
        die;
    } else {
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

// Function to sort given values alphabetically
function alphasort($a, $b) {
    $specialChars = array("$", '-', '_', '.', '+', '!', '*', "'", '(', ')', ',');
    return strcasecmp(str_replace($specialChars, '\\', $a->getPathname()), str_replace($specialChars, '\\', $b->getPathname()));
}

// Class to put forward the values for sorting
class SortingIterator implements IteratorAggregate {
    private $iterator = null;

    public function __construct(Traversable $iterator, $callback) {
        $array = iterator_to_array($iterator);
        usort($array, $callback);
        $this->iterator = new ArrayIterator($array);
    }

    public function getIterator() {
        return $this->iterator;
    }
}

function showTree($path) {
    $excludeFileFolder = '..';

    $objectList = new SortingIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST), 'alphasort');

    // With that done, create arrays for out final ordered list and a temp array of files to copy over
    $finalArray = $tempArray =  array();

    // To start, push folders from object into finalArray, files into tempArray
    foreach ($objectList as $objectRef) {
        $fileFolderName = rtrim(substr($objectRef->getPathname(), strlen($path)));
        if ($objectRef->getFilename() != '.' && $fileFolderName[strlen($fileFolderName)-1] != '/' && !strpos($fileFolderName, $excludeFileFolder)) {
            $fileFolderName != '/' && is_dir($path.$fileFolderName) ? array_push($finalArray, $fileFolderName) : array_push($tempArray, $fileFolderName);
        }
    }

    // Now push root files onto the end of finalArray and splice from the temp, leaving only files that reside in subsirs
    for ($i = 0; $i<count($tempArray);
    $i++) {
        if (count(explode('/', $tempArray[$i])) == 2) {
            array_push($finalArray, $tempArray[$i]);
            array_splice($tempArray, $i, 1);
            $i--;
        }
    }

    // Lastly we push remaining files into the right subdirs in finalArray
    for ($i = 0; $i<count($tempArray);
    $i++) {
        $insertAt = array_search(dirname($tempArray[$i]), $finalArray)+1;
        for ($j = $insertAt; $j<count($finalArray);
        $j++) {
            if (strcasecmp(dirname($finalArray[$j]), dirname($tempArray[$i])) == 0 &&
            strcasecmp(basename($finalArray[$j]), basename($tempArray[$i]))<0 ||
            strstr(dirname($finalArray[$j]), dirname($tempArray[$i]))) {
                $insertAt++;
            }
        }
        array_splice($finalArray, $insertAt, 0, $tempArray[$i]);
    }

    // Finally, we have our ordered list, so display in a UL
    echo '<ul><li>/</li>';
    $lastPath = '';
    for ($i = 0; $i<count($finalArray);
    $i++) {
        $fileFolderName = $finalArray[$i];
        $thisDepth = count(explode('/', $fileFolderName));
        $lastDepth = count(explode('/', $lastPath));
        if ($thisDepth > $lastDepth) {
            echo '<ul>\n';
        }
        if ($thisDepth < $lastDepth) {
            for ($j = $lastDepth; $j>$thisDepth; $j--) {
                echo '</ul>';
            }
        }
        echo '<li>'.basename($fileFolderName).'</li>';
        $lastPath = $fileFolderName;
    }
    echo '</ul></ul>';
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php print $_GET['note']; ?></title>
    <link rel="icon" href="<?php print $base_url; ?>/favicon.ico" sizes="any">
    <link rel="icon" href="<?php print $base_url; ?>/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="<?php print $base_url; ?>/styles.css">
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Roboto&display=swap' rel='stylesheet'>
</head>

<body>
    <div class='left'>
        <span>
            <?php
                $rootDirectory = '/path/your/folder/_tmp';
                showTree($rootDirectory);
                ?>
        </span>
    </div>
    <div class='right'>
        <textarea id='content'><?php if (is_file($path)) {print htmlspecialchars(file_get_contents($path), ENT_QUOTES, 'UTF-8');}?></textarea>
        <pre id='printable'></pre>
    </div>
    <script src="script.js"></script>
</body>

</html>