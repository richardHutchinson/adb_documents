<?
	ini_set("mongo.cmd", ":");
	$mongo = new Mongo();
	$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');

	if(empty($_POST["new_title"])){
		$Name="";
		echo json_encode(array("Unsuccessful" => 0));
		die();
	}else{
		$Name=$_POST["new_title"];
	}
	
	if(empty($_POST["new_artist"])){
		$Artist="";
	}else{
		$Artist=$_POST["new_artist"];
	}
	
	if(empty($_POST["new_genre"])){
		$Genre="";
	}else{
		$Genre=$_POST["new_genre"];
	}
	
	if(empty($_POST["new_kind"])){
		$Kind="";
	}else{
		$Kind=$_POST["new_kind"];
	}
	
	if(empty($_POST["new_TotalTime"])){
		$TotalTime="";
	}else{
		$TotalTime=$_POST["new_TotalTime"];
	}

	$PersistentID = substr(uniqid(rand(), true),1,13);	
	$newTrack = array("_id" => "track:".$PersistentID, "PersistentID" => $PersistentID, "type" => "track", "Name" => $Name, "Artist" => $Artist, "Genre" => $Genre, "Kind" => $Kind, "TotalTime" => $TotalTime);

	$itunes->save($newTrack);

	echo json_encode(array("Successful" => 1));
	echo "<a href='index.php'>Home</a>";

	//http://localhost:8888/ADB/lab3/insertTrack.php?Name=Test%20Title&Artist=Artist&Genre=Testmusic&Kind=Mp3&TotalTime=123456789
?>