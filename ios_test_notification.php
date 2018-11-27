<?php
    function send_iPhone_Notification(){
		
		echo $deviceToken = "08e42f5445aa904fd71e8d805196259ef3767aacdf31b72cae96590121f6dcb9";
		$text = "Test";
		
		// Put your private key's passphrase here:
		$passphrase = @"1234";

		// Put your alert message here:
		$message = @$text ;

		////////////////////////////////////////////////////////////////////////////////

		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', 'carxiDriverDevMac.pem');
		
		//stream_context_set_option($ctx, 'ssl', 'local_cert', 'oldck.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
		$fp = stream_socket_client(
			//'ssl://gateway.sandbox.push.apple.com:2195', $err,
			'ssl://gateway.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);

		echo 'Connected to APNS' . PHP_EOL;
		
		//$rem_id1 = 83;$vec_id1 = 444;
		// Create the payload body
		$body['aps'] = array(
			'alert' => $message,
			'vibrate' => 1,
			'ntype' => '0',
			'sound' => 'default'
			);

		// Encode the payload as JSON
		$payload = json_encode($body);

		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));

		if (!$result){
			echo 'Message not delivered' . PHP_EOL;
		}
		else{
			echo 'Message successfully delivered' . PHP_EOL;
		}
		// Close the connection to the server
		//echo "Date";
		fclose($fp);
		
    }
    
    send_iPhone_Notification();
?>