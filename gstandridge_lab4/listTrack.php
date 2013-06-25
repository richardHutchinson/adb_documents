<?
	session_start();
	ini_set("mongo.cmd", ":");
	$mongo = new Mongo();
	$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');

	if(empty($_GET["pId"])){
		echo json_encode(array("unsuccessful"=>0));
	}elseif(!empty($_GET["pId"])){
		$track = $itunes->findOne(array('PersistentID' => $_GET["pId"]));
	}

	$PersistentID = '';
	if(array_key_exists('PersistentID',$track)){
		$PersistentID = $track['PersistentID'];
	}

	$Name = '';
	if (array_key_exists('Name',$track)){
		$Name = $track['Name'];
	}

	$Artist = '';
	if (array_key_exists('Artist',$track)){
		$Artist = $track['Artist'];
	}

	$Genre = '';
	if (array_key_exists('Genre',$track)){
		$Genre = $track['Genre'];
	}

	$Kind = '';
	if (array_key_exists('Kind',$track)){
		$Kind = $track['Kind'];
	}

	$TotalTime = '';
	if (array_key_exists('TotalTime',$track)){
		$TotalTime = $track['TotalTime'];
	}

	$jsonArray[] = array('PersistentID'=>$PersistentID,'Name'=>$Name,'Artist'=>$Artist,'Genre'=>$Genre,'Kind'=>$Kind,'TotalTime'=>$TotalTime);

	return array("track"=>$jsonArray);
?>