<?php

$hostFile = getenv('HOSTS_IMPORTER__HOSTS_FILE') ?: '/etc/hosts';
$hostFileUrl = getenv('HOSTS_IMPORTER__HOSTS_FILE_URL') ?: 'https://raw.githubusercontent.com/StevenBlack/hosts/master/alternates/fakenews-gambling-porn-social/hosts';
$hostFileMarkerStart = getenv('HOSTS_IMPORTER__MARKER_START') ?: '###HOSTS_IMPORTER_START###';
$hostFileMarkerEnd = getenv('HOSTS_IMPORTER__MARKER_END') ?: '###HOSTS_IMPORTER_END###';
$hostFileUrlBackupNumber = 5;

try {
    if (!file_exists($hostFile)) {
        throw new \Exception('Host file under: ' . $hostFile . ' does not exists.');
    }

    echo('1. Download hosts file from ' . $hostFileUrl . ' and store it temporary for security check... ');
    $downloadedHostsFilename = sys_get_temp_dir() . '/hosts_installer_rawhosts' .
        rand(1, 10000000) . time() . '.txt';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $hostFileUrl);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    if ($result !== false) {
        file_put_contents($downloadedHostsFilename, curl_exec($ch));
    } else {
        throw new \Exception('Could not download: ' . $hostFileUrl);
    }
    curl_close($ch);
    echo('done.' . PHP_EOL);


    echo('2. Mix the current host file content with downloaded host entries... ');
    $currentHostFileLineCount = 0;
    $currentHostFileHandle = fopen($hostFile, "r");
    while (!feof($currentHostFileHandle)) {
        $line = fgets($currentHostFileHandle);
        $currentHostFileLineCount++;
    }
    fclose($currentHostFileHandle);
    $newHostFile = sys_get_temp_dir() . '/hosts_installer_newhostfile' . rand(1, 10000000) . time() . '.txt';
    $downloadedHostsFileHandle = fopen($downloadedHostsFilename, 'r');
    $currentHostFileHandle = fopen($hostFile, 'r');
    $stopAdding = false;
    $newHostFileLineCount = 0;
    while (!feof($currentHostFileHandle)) {
        $newHostFileLineCount++;
        $currentHostFileLine = trim(fgets($currentHostFileHandle), "\r\n ");
        if (strpos($currentHostFileLine, $hostFileMarkerStart) !== false) {
            file_put_contents($newHostFile,
                $hostFileMarkerStart . PHP_EOL . '# Updated on: ' . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
            while (!feof($downloadedHostsFileHandle)) {
                $lineExploded = explode(' ', rtrim(fgets($downloadedHostsFileHandle), "\r\n"), 2);
                if (!empty($lineExploded[0]) && $lineExploded[0] === '0.0.0.0') {
                    file_put_contents($newHostFile,
                        $lineExploded[0] . ' ' . $lineExploded[1] . PHP_EOL,
                        FILE_APPEND);
                }
            }
            file_put_contents($newHostFile, $hostFileMarkerEnd . PHP_EOL, FILE_APPEND);
            $stopAdding = true;
            continue;
        }
        if (strpos($currentHostFileLine, $hostFileMarkerEnd) !== false) {
            $stopAdding = false;
            continue;
        }
        if ($stopAdding === false) {
            file_put_contents($newHostFile,
                $currentHostFileLine . ($currentHostFileLineCount === $newHostFileLineCount ? '' : PHP_EOL),
                FILE_APPEND);
        }
    }
    unlink($downloadedHostsFilename);
    fclose($downloadedHostsFileHandle);
    echo('done.' . PHP_EOL);


    echo('3. Make backup of current hosts file and do cleanup of old backups... ');
    copy($hostFile, $hostFile . '.' . date('YmdHis') . '.secure-hosts-importer.backup');
    $backupCount = 0;
    $files = glob($hostFile . '.[0-9]*.secure-hosts-importer.backup');
    natsort($files);
    foreach (array_reverse($files) as $file) {
        if ($backupCount++ >= $hostFileUrlBackupNumber) {
            unlink($file);
        }
    }
    echo('done.' . PHP_EOL);


    echo('4. Replace current hosts file with new one... ');
    copy($newHostFile, $hostFile);
    unlink($newHostFile);
    echo('done.' . PHP_EOL);

} catch (\Exception $e) {
    echo $e->getMessage();
}