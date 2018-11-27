<?php
 
$url =  "https://auth.jive.com/oauth2/v2/grant?response_type=token&client_id=ee5df1c5-9104-4658-a3e7-946f1d61760c&redirect_uri=http://getcarxi.com/callback.php&scope=%20";

$result = file_get_contents($url);
 
var_dump(json_decode($result));