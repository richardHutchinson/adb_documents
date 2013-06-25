<?
	ini_set("mongo.cmd", ":");
	$mongo = new Mongo();
	$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');

	if(empty($_POST["track_pId"])){
		echo json_encode(array("Unsuccessful" => 0));
		die();
	}else{
		$updatedTrack = $itunes->findOne(array("PersistentID"=>$_POST["track_pId"]));
	}

	if(!empty($_POST["track_title"])){
		$updatedTrack["Name"]=$_POST["track_title"];
	}
	if(!empty($_POST["track_artist"])){
		$updatedTrack["Artist"]=$_POST["track_artist"];
	}
	if(!empty($_POST["track_genre"])){
		$updatedTrack["Genre"]=$_POST["track_genre"];
	}
	if(!empty($_POST["track_kind"])){
		$updatedTrack["Kind"]=$_POST["track_kind"];
	}

	//var_dump($updatedTrack);
	$itunes->save($updatedTrack);
	echo json_encode(array("Successful" => 1));
	echo "<a href='index.php'>Home</a>";

	//http://localhost:8888/ADB/lab3/updateTrack.php?_id=???
?>