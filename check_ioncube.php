<?php
function isIonCubeEncoded($filePath) {
    $handle = @fopen($filePath, 'r');
    if (!$handle) return false;

    $firstBytes = fread($handle, 512); // Read first 512 bytes
    fclose($handle);

    return (
        strpos($firstBytes, 'ionCube') !== false ||
        strpos($firstBytes, '@Zend') !== false ||
        strpos($firstBytes, "<?php //") !== false && preg_match('/^\<\?php\s\/\/[0-9a-f]{5,}/i', $firstBytes)
    );
}

function scanDirectory($dir) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $encodedFiles = [];

    foreach ($rii as $file) {
        if ($file->isDir()) continue;
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;

        if (isIonCubeEncoded($file->getPathname())) {
            $encodedFiles[] = $file->getPathname();
        }
    }

    return $encodedFiles;
}

// Define scan path
$directoryToScan = __DIR__;
$results = scanDirectory($directoryToScan);

// Prepare log message
$logFile = __DIR__ . '/ioncube_scan_log.txt';
$logDate = date('Y-m-d H:i:s');
$logContent = "ionCube Scan Report - {$logDate}\n";
$logContent .= "Scanned directory: $directoryToScan\n\n";

if (empty($results)) {
    $logContent .= "✅ No ionCube-encoded files found.\n";
    echo "✅ No ionCube-encoded files found. Log written to $logFile\n";
} else {
    $logContent .= "⚠️ Found ionCube-encoded files:\n";
    foreach ($results as $file) {
        $logContent .= " - $file\n";
    }
    echo "⚠️ ionCube-encoded files found. Log written to $logFile\n";
}

// Write to log file
file_put_contents($logFile, $logContent, FILE_APPEND);
