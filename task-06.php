<?php


$fileA = "./test-files/fileA.txt";
$fileB = "./test-files/fileB.txt";
$result = Compare($fileA, $fileB);

//Need tp cecks file are exists and readable...

var_dump($result);

function Compare($p_A_filename, $p_B_filename): bool
{
    $chunkSize = 1024;
    $fileA = fopen($p_A_filename, 'rb');
    $fileB = fopen($p_B_filename, 'rb');
    $checksumA = 0;
    $checksumB = 0;

    while (!feof($fileA)) {
        $bufferA = fgets($fileA, $chunkSize);
        $length = strlen($bufferA);

        if ($length === 0) {
            break;
        }

        $bufferB = fread($fileB, $length);

        if (strlen($bufferB) < $length) {
            fclose($fileA);
            fclose($fileB);
            return false;
        }

        $checksumA = MyChkSum($bufferA, $length, $checksumA);
        $checksumB = MyChkSum($bufferB, $length, $checksumB);

        if ($checksumA === -1 || $checksumB === -1 || $checksumA !== $checksumB) {
            fclose($fileA);
            fclose($fileB);
            return false;
        }
    }
    fclose($fileA);
    fclose($fileB);
    return true;
}

function MyChkSum($buff, $len, $prevchk)
{
    if ($len > 1024) {
        return -1; // error (len > 1024
    }

    $checksum = $prevchk;
    for ($i = 0; $i < $len; $i++) {
        $checksum += ord($buff[$i]);
    }
    return $checksum;
}
