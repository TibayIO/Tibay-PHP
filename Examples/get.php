<?php
require '../client.class.php';
$myNetwork = new Tibay('g1YJxwZrJd4Celsw', 'IpxkSRbDAwiIuDt');
$myNetwork->connect();
while (true) {
	echo 'Got: ';
	var_dump($myNetwork->get());
	echo "\n";
}
?>