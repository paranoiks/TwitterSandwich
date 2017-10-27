<?php

require_once('twitteroauth/autoload.php');
require_once('databaseWrapper.php');

use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth($config['consumer_key'], $config['consumer_secret'], $config['access_token'], $config['access_token_secret']);
$content = $connection->get("account/verify_credentials");

$statuses = $connection->get("search/tweets", ["q" => "#sandwich -sex -porn"]);

$numberOfStatuses = count($statuses->statuses);

$maxScore = -1;
$maxScoreTweetId = -1;
$statusToPost = NULL;

foreach ($statuses->statuses as $status) 
{	
	$currentStatusScore = $status->favorite_count + $status->retweet_count;
	if($status->entities->media != NULL)
	{
		$currentStatusScore += 100000;
	}

	if($currentStatusScore > $maxScore)
	{
		$maxScore = $currentStatusScore;
		$maxScoreTweetId = $status->id;
		$statusToPost = $status;
	}
}

$retweet = $connection->post("statuses/retweet", ["id" => $maxScoreTweetId]);

if($statusToPost->id == $maxScoreTweetId)
{
	if($statusToPost->entities->media != NULL)
	{
		//INSERT the image in the database for later use
		$dbRecordTweetId = $statusToPost->id;
		$dbRecordUserId = $statusToPost->user->id_str;
		$dbRecordImageUrl = $statusToPost->entities->media[0]->media_url;
		InsertImageURL($dbRecordTweetId, $dbRecordUserId, $dbRecordImageUrl);
	}
}

?>