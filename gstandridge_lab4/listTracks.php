<?
	session_start();
	ini_set("mongo.cmd", ":");
	$mongo = new Mongo();
	$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');
	if(!empty($_GET["page"])){
		$_SESSION["currentPage"] = $_GET["page"];
	}

	if(empty($_GET["page"])){
		$tracks = $itunes->find(array('type' => 'track'))->sort(array('Name'=>1))->limit(10);
	}elseif(!empty($_GET["page"])){
		$skip = $_GET["page"] * 10 - 10;
		$tracks = $itunes->find(array('type' => 'track'))->sort(array('Name'=>1))->limit(10)->skip($skip);
	}

	foreach($tracks as $json){

		$PersistentID = '';
		if (array_key_exists('PersistentID',$json)){
			$PersistentID = $json['PersistentID'];
		}

		$Name = '';
		if (array_key_exists('Name',$json)){
			$Name = $json['Name'];
		}
		
		$Artist = '';
		if (array_key_exists('Artist',$json)){
			$Artist = $json['Artist'];
		}

		$Genre = '';
		if (array_key_exists('Genre',$json)){
			$Genre = $json['Genre'];
		}

		$Kind = 0;
		if (array_key_exists('Kind',$json)){
			$Kind = $json['Kind'];
		}

		$TotalTime = '';
		if (array_key_exists('TotalTime',$json)){
			$TotalTime = $json['TotalTime'];
		}

		$jsonArray[] = array('PersistentID'=>$PersistentID,'Name'=>$Name,'Artist'=>$Artist,'Genre'=>$Genre,'Kind'=>$Kind,'TotalTime'=>$TotalTime);
	}
	echo json_encode(array("tracks"=>$jsonArray));
?>