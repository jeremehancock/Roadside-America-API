<?php

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

<!-- Default Statcounter code for Roadside America (API) https://roadside-america.dumbprojects.com/apicts.com/ -->
<script>
    var sc_project=12101529; 
    var sc_invisible=1; 
    var sc_security="de62c209"; 
</script>
<script src="https://www.statcounter.com/counter/counter.js"async></script>
<noscript>
    <div class="statcounter">
        <a title="Web Analytics Made Easy - StatCounter" href="https://statcounter.com/" target="_blank">
        <img class="statcounter" src="https://c.statcounter.com/12101529/0/de62c209/1/" alt="Web Analytics Made Easy -StatCounter">
    </a>
</div>
</noscript>
<!-- End of Statcounter Code -->