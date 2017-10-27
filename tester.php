<?php

//READY TO BE DELETED

require_once('twitteroauth/autoload.php');
require_once('databaseWrapper.php');

use Abraham\TwitterOAuth\TwitterOAuth;



$connection = new TwitterOAuth($config['consumer_key'], $config['consumer_secret'], $config['access_token'], $config['access_token_secret']);
$content = $connection->get("account/verify_credentials");


$statuses = $connection->get("search/tweets", ["q" => "#sandwich"]);

foreach($statuses->statuses as $status)
{
	echo "</br>";
	if($status->entities->media != NULL)
	{
		print_r($status->entities->media[0]->media_url);
	}
	echo "</br>";
	echo "</br>";

}

?>