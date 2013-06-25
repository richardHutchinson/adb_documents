<?php
require_once 'CFDump.php';

ini_set("mongo.cmd", ":");
$mongo = new Mongo();
$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');
$shortTracks = $itunes->find(array('TotalTime' => array(':gt' => 0)));

echo 'Document count: ' . $shortTracks->count();

$loopcount = 0;
foreach ($shortTracks as $mongoTrack) {
	$loopcount++;
	
	echo '<li>'. 
			'Name:' . $mongoTrack['Name'].
			'<b> ( </b>' . $mongoTrack['PersistentID'].'<b> ) </b>'.
			'</li>';
			
	$Album = '';
	if (array_key_exists('Album',$mongoTrack)){
		$Album = $mongoTrack['Album'];
	} 
	
	$Artist = '';
	if (array_key_exists('Artist',$mongoTrack)){
		$Artist = $mongoTrack['Artist'];
	}
	
	$Year = '';
	if (array_key_exists('Year',$mongoTrack)){
		$Year = $mongoTrack['Year'];
	}
	
	$playCount = 0;
	if (array_key_exists('playCount',$mongoTrack)){
		$playCount = $mongoTrack['playCount'];
	} 
	
	$TotalTime = '';
	if (array_key_exists('TotalTime',$mongoTrack)){
		$TotalTime = $mongoTrack['TotalTime'];
	} 
	
	$Size = 0;
	if (array_key_exists('Size',$mongoTrack)){
		$Size = $mongoTrack['Size'];
	} 
	
	$Name = 0;
	if (array_key_exists('Name',$mongoTrack)){
		$Name = $mongoTrack['Name'];
	} 
	
	// Add updateDate
	 $obj = array(
		"_id"=>$mongoTrack['_id'],
		"PersistentID"=>$mongoTrack['PersistentID'],
		"Name"=>$Name,
		"Album"=>$Album,
		"Artist"=>$Artist,
		"Size"=>$Size,
		"Year"=>$Year,
		"playCount"=>$playCount,
		"updateDate"=>'01/01/2012',
		"TotalTime"=>$TotalTime
		);
	$itunes->save($obj); 
	
}

