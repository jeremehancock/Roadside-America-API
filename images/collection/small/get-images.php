<?php

for ($i = 1; $i <= 11704; $i++) {
	$number = sprintf("%05d", $i);
    $api_url = "https://loc.gov/pictures/resource/mrg." . $number . "?fo=json";
    $response = file_get_contents($api_url);
    $q_array = json_decode($response, true);

    $large = $q_array["resource"]["large"];
    $medium = $q_array["resource"]["medium"];
    $small = $q_array["resource"]["small"];

    $url = "https:" . $small;
    
    // Use basename() function to return the base name of file  
	//$file_name = basename($url); 
	
	$file_name = $i . ".jpg";
    
    if(file_put_contents( $file_name,file_get_contents($url))) {
        echo $i . " of 11704 downloaded successfully\n";
    }
    else {
        echo "File downloading failed\n";
    }
}

?>
        
