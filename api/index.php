<?php

header("Access-Control-Allow-Origin: *");
header("Content-type:application/json");

// Configuration
$max_retries = 3;
$cache_duration = 3600; // 1 hour cache
$cache_dir = sys_get_temp_dir() . '/loc_cache/';

// Create cache directory if it doesn't exist
if (!file_exists($cache_dir)) {
    mkdir($cache_dir, 0777, true);
}

// Generate random number
$random = rand(1, 11704);
$number = sprintf("%05d", $random);
$api_url = "https://loc.gov/pictures/resource/mrg." . $number . "?fo=json";

// Check cache first
$cache_file = $cache_dir . md5($api_url) . '.json';
if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_duration)) {
    echo file_get_contents($cache_file);
    exit;
}

// Function to make API request with retry logic
function fetchWithRetry($url, $max_retries)
{
    $attempt = 0;

    while ($attempt < $max_retries) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; PHP Script)',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json'
            ]
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Success
        if ($http_code == 200 && $response !== false) {
            return ['success' => true, 'data' => $response];
        }

        // Rate limit hit - use exponential backoff
        if ($http_code == 429) {
            $attempt++;
            if ($attempt < $max_retries) {
                $wait_time = pow(2, $attempt); // 2, 4, 8 seconds
                sleep($wait_time);
                continue;
            }
        }

        // Other error
        return ['success' => false, 'error' => "HTTP $http_code", 'attempt' => $attempt];
    }

    return ['success' => false, 'error' => 'Max retries exceeded'];
}

// Fetch data
$result = fetchWithRetry($api_url, $max_retries);

if (!$result['success']) {
    http_response_code(503);
    echo json_encode([
        "error" => "Unable to fetch data from Library of Congress",
        "details" => $result['error']
    ]);
    exit;
}

// Parse response
$q_array = json_decode($result['data'], true);

if (!$q_array || !isset($q_array["item"])) {
    http_response_code(500);
    echo json_encode(["error" => "Invalid response from API"]);
    exit;
}

// Extract data
$title = $q_array["item"]["title"] ?? "Unknown";
$large = $q_array["resource"]["large"] ?? null;
$medium = $q_array["resource"]["medium"] ?? null;
$small = $q_array["resource"]["small"] ?? null;
$source = $q_array["item"]["resource_links"][0] ?? null;

// Create response
$json = json_encode([
    "results" => [
        "title" => $title,
        "source" => $source,
        "images" => [
            "large" => $large,
            "medium" => $medium,
            "small" => $small
        ]
    ]
]);

// Cache the response
file_put_contents($cache_file, $json);

echo $json;