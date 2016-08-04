<?php
class Tibay {
	function __construct($key, $pass, $protocol=1, $timeout=10, $debug=false) {
		$this->key = $key;
		$this->pass = $pass;
		$this->protocol = $protocol;
		$this->socket = false;
		$this->timeout = $timeout;
		$this->debug = $debug;
	}
	function debug($message) {
		if ($this->debug) {
			echo $message."\n";
		}
	}
	function connect($host='api.tibay.io', $port=12673) {
		$this->debug("Connecting ssl://{$host}:{$port}");
		$context = stream_context_create(['ssl'=>['verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>true]]);
		if (!$this->socket = @stream_socket_client("ssl://{$host}:{$port}", $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $context)) {
			$this->debug('Unable to connect daemon: '.$errstr);
			return false;
		}
		stream_set_timeout($this->socket, $this->timeout);
		return $this->authenticate();
	}
	function checkConnection() {
		if (!is_resource($this->socket)) {
			// Disconnected, try to reconnect
			if (!$this->connect()) {
				return false;
			}
		}
		$meta = stream_get_meta_data($this->socket);
		if ($meta['eof']) {
			// Disconnected, try to reconnect
			if (!$this->connect()) {
				return false;
			}
		}
		return true;
	}
	function authenticate() {
		$data = [
			'key' => $this->key,
			'pass' => $this->pass,
			'protocol' => $this->protocol
		];
		if (!$this->send($data, false)) {
			$this->debug('Unable to send data to daemon');
			return false;
		}
		$ret = $this->get(true, false);
		if ($ret['ret'] == 'Hellno!') {
			return true;
		}
		return false;
	}
	function send($data, $checkConnection=true) {
		if ($checkConnection && !$this->checkConnection()) {
			sleep(1);
			return false;
		}
		$data = json_encode($data);
		while (!@fwrite($this->socket, $data.'!-EOF-!')) {
			if (!$this->connect()) {
				return false;
			}
		}
		return true;
	}
	function get($wait=true, $checkConnection=true) {
		if ($checkConnection && !$this->checkConnection()) {
			sleep(1);
			return false;
		}
		$read = [$this->socket];
		$write = NULL;
		$eexcept = NULL;
		if ($wait || (!$wait && stream_select($read, $write, $except, $this->timeout) > 0)) {
			$data = stream_get_line($this->socket, 10240000, '!-EOF-!');
			$data = trim($data);
			return json_decode($data, true);
		}
		return;
	}
}
?>