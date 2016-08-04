<?php
require '../client.class.php';
$myNetwork = new Tibay('g1YJxwZrJd4Celsw', 'IpxkSRbDAwiIuDt');
$myNetwork->connect();
while (true) {
	echo "Sending...\n";
	$myNetwork->send('This is my test message');
	sleep(3);
}
?>