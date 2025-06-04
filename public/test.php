<?php
echo "Laravel Test Page";
echo "<br>Current directory: " . getcwd();
echo "<br>Document root: " . $_SERVER['DOCUMENT_ROOT'] ?? 'Not set';
echo "<br>Script name: " . $_SERVER['SCRIPT_NAME'] ?? 'Not set';
echo "<br>Request URI: " . $_SERVER['REQUEST_URI'] ?? 'Not set';
echo "<br>PHP Version: " . phpversion();

// Check if Laravel bootstrap exists
if (file_exists(__DIR__.'/../bootstrap/app.php')) {
    echo "<br>Laravel bootstrap found!";
} else {
    echo "<br>Laravel bootstrap NOT found!";
}

// Check if vendor autoload exists
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    echo "<br>Vendor autoload found!";
} else {
    echo "<br>Vendor autoload NOT found!";
}
?>
