<?php
$fp = fsockopen("smtp.office365.com", 587, $errno, $errstr, 10);

if (!$fp) {
    echo "FAILED: $errstr ($errno)";
} else {
    echo "CONNECTED";
    fclose($fp);
}
