<?php

require_once('twitteroauth/autoload.php');
require_once('databaseWrapper.php');

use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth($config['consumer_key'], $config['consumer_secret'], $config['access_token'], $config['access_token_secret']);
$content = $connection->get("account/verify_credentials");


//get all images from the database
$records = GetAllSavedImages();

//choose a random record
$numberOfRecords = count($records);
$randomIndex = rand(0, $numberOfRecords - 1);
$record = $records[$randomIndex];

//download the image
$tweetId = $record['tweet_id'];
$imageUrl = $record['image_url'];
$content = file_get_contents($imageUrl);
$imagePath = "tempImage" . $tweetId . ".jpg";
$fp = fopen($imagePath, "w");
fwrite($fp, $content);
fclose($fp);

//upload and post the image
$media1 = $connection->upload('media/upload', ['media' => $imagePath]);
$parameters = [
    'status' => 'Sandwiches always bring back happy memories #tt #throwbackthursday',
    'media_ids' => implode(',', [$media1->media_id_string])
];
$result = $connection->post('statuses/update', $parameters);

//delete the image
unlink($imagePath);

//empty the table
DeleteAllImages();

?>