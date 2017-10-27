<?php

require_once('twitteroauth/autoload.php');
require_once('config.php');


use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth($config['consumer_key'], $config['consumer_secret'], $config['access_token'], $config['access_token_secret']);
$content = $connection->get("account/verify_credentials");


?>