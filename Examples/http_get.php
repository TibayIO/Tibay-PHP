<?php
// simple http get without Tibay class
$data = array(
	'key' => 'g1YJxwZrJd4Celsw', // enter yor network key here
	'pass' => 'IpxkSRbDAwiIuDt',  // enter your nework pass here
	'protocol' => 1,
	'data' => 'Some example data'
);
echo 'Getting: https://api.tibay.io?'.http_build_query($data)."\n";
$return = file_get_contents('https://api.tibay.io?'.http_build_query($data));
$return = json_decode($return);
echo '<pre>';
print_r($return);
echo '</pre>';
?>