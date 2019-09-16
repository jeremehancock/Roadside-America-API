<?php

header("Access-Control-Allow-Origin: *");
header("Content-type:application/json");

$random = rand(1,11704);

$api_url = "https://www.loc.gov/pictures/search/?q=mrg&fa=displayed%3Aanywhere&c=1&sp=" . $random . "&fo=json";

$response = file_get_contents($api_url);
$q_array = json_decode($response, true);

$title =  $q_array[results][0][title];

$full = $q_array[results][0][image][full];
$square = $q_array[results][0][image][square];
$thumb = $q_array[results][0][image][thumb];

$item =  $q_array[results][0][links][item];
$resource = $q_array[results][0][links][resource];

/* Set json array */
$json = json_encode(array(
    results => array(
        title => "$title",
        links => array(
            item => $item,
            resource => $resource
        ),
        images => array(
            full => $full,
            square => "https:" . $square,
            thumb => "https:" . $thumb
        )
    )
));

echo $json;

?>