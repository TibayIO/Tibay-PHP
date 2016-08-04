<?php
// simple http post without Tibay class
$data = array(
	'key' => 'g1YJxwZrJd4Celsw', // enter yor network key here
	'pass' => 'IpxkSRbDAwiIuDt',  // enter your nework pass here
	'protocol' => 1,
	'data' => 'Some example data'
);
$opts = ['http' => [
		'method' => 'POST',
		'header' => 'Content-type: application/x-www-form-urlencoded',
		'content' => http_build_query($data)
	]
];
$return = file_get_contents('https://api.tibay.io?', false, stream_context_create($opts));
$return = json_decode($return);
echo '<pre>';
print_r($return);
echo '</pre>';
?>