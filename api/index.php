<?php

header("Access-Control-Allow-Origin: *");
header("Content-type:application/json");

$random = rand(1,11704);

$number = sprintf("%05d", $random);

$api_url = "https://loc.gov/pictures/resource/mrg." . $number . "?fo=json";

$response = file_get_contents($api_url);
$q_array = json_decode($response, true);

$title =  $q_array["item"]["title"];

$large = $q_array["resource"]["large"];
$medium = $q_array["resource"]["medium"];
$small = $q_array["resource"]["small"];

$source =  $q_array["item"]["resource_links"][0];

/* Set json array */
$json = json_encode(array(
    "results" => array(
        "title" => $title,
        "source" => $source,
        "images" => array(
            "large" => $large,
            "medium" => $medium,
            "small" => $small
        )
    )
));

echo $json;
