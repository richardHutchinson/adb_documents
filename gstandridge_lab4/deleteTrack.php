<?
	ini_set("mongo.cmd", ":");
	$mongo = new Mongo();
	$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');

	if(empty($_GET["pId"])){
		echo json_encode(array("Unsuccessful" => 0));
		die();
	}else{
		$deletedTrack = array("PersistentID" => $_GET["pId"]);
		$itunes->remove($deletedTrack);
	}
	echo json_encode(array("Successful" => 1));
	echo "<a href='index.php'>Home</a>";

	//http://localhost:8888/ADB/lab3/deleteTrack.php?_id=???
?>