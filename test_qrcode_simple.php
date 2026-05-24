<?php
require 'vendor/autoload.php';

// Instantiate the application to bootstrap Laravel facades or just use the class directly
// QrCode facade maps to SimpleSoftwareIO\QrCode\Generator.
$generator = new \SimpleSoftwareIO\QrCode\Generator();
$pngData = $generator->format('png')->size(100)->generate('hello');
echo "TYPE: " . gettype($pngData) . "\n";
echo "LENGTH: " . strlen($pngData) . "\n";
echo "BASE64: " . substr(base64_encode($pngData), 0, 100) . "\n";
unlink(__FILE__);
