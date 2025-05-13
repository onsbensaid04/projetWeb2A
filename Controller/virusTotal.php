<?php

if (!isset($_GET['url'])) {
    echo json_encode(['error' => 'Missing URL']);
    exit;
}

$apiKey = 'ad272a454d33f0ed74ecc9f59092270282f2f96aa0a5b5ea7f575f89253a8ee5';
$urlToCheck = $_GET['url'];

// Step 1: Submit URL for scanning
$ch = curl_init("https://www.virustotal.com/api/v3/urls");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "x-apikey: $apiKey",
    "Content-Type: application/x-www-form-urlencoded"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, "url=" . urlencode($urlToCheck));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$scanData = json_decode($response, true);
curl_close($ch);

if (!isset($scanData['data']['id'])) {
    echo json_encode(['safe' => false, 'error' => 'Scan ID not found']);
    exit;
}

$analysisId = $scanData['data']['id'];

// Step 2: Get scan report
sleep(2); // Wait briefly for analysis
$ch = curl_init("https://www.virustotal.com/api/v3/analyses/$analysisId");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "x-apikey: $apiKey"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$report = curl_exec($ch);
curl_close($ch);

echo $report;
