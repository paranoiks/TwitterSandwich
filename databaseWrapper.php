<?php

require_once('config.php');

function Connect()
{
	global $config;

	$link = mysqli_connect('localhost', $config['database_username'], $config['database_password'], $config['database_name']);

	if (!$link) 
	{
	    echo "Error: Unable to connect to MySQL." . PHP_EOL;
	    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	    exit;
	}

	return $link;
}

function Disconnect($link)
{
	mysqli_close($link);
}

function InsertImageURL($tweetId, $userId, $imageUrl)
{
	$link = Connect();

	//make sure that we only insert unique tweets. No need to put the same tweet in here twice
	$query = 
	"INSERT INTO imagesUrls (tweet_id, user_id, image_url)
	 SELECT * FROM (SELECT '".$tweetId."', '".$userId."', '".$imageUrl."') AS tmp
	 WHERE NOT EXISTS (
     SELECT tweet_id FROM imagesUrls WHERE tweet_id = '".$tweetId."'
	 ) LIMIT 1;";

	mysqli_query($link, $query);

	Disconnect($link);
}

function GetAllSavedImages()
{
	$link = Connect();

	$query = "SELECT * FROM imagesUrls";

	$result = mysqli_query($link, $query);

	$data = array();
	$index = 0;
	while ($row = mysqli_fetch_assoc($result)) 
	{		
		$data[$index] = $row; 
		$index++;
	}

	Disconnect($link);

	return $data;
}

function DeleteAllImages()
{
	$link = Connect();

	$query = "TRUNCATE imagesUrls";

	mysqli_query($link, $query);

	Disconnect($link);
}

?>