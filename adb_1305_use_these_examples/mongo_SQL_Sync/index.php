<?
	$sqlDb->dbcon = new PDO("mysql:host=localhost;dbname=itunesMongo","root","root");
	$sqlDb->dbcon->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	ini_set("mongo.cmd", ":");
	$mongo = new Mongo();
	$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');

	$tracks = $itunes->find(array('type' => 'track'));
	
	/*	Get all Mongo tracks.
		Loop through tracks, check MySQL is the track exists.
		If the record does not exits in MySQL, insert.
		If the record exists, but any of the fields do not match, update MySQL.
	*/
	foreach ($tracks as $track) {
		$check = " select PersistentID, Name, Artist, Genre, Kind, TotalTime from tracks where PersistentID = '".$track['PersistentID']."'";
		$checkResult = $sqlDb->dbcon->prepare($check);
		$checkResult->execute(array($track['PersistentID']));
		$checkedTrack = $checkResult->fetchAll();
		
		$PersistentID = '';
		if (array_key_exists('PersistentID',$track)){
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

		$Kind = 0;
		if (array_key_exists('Kind',$track)){
			$Kind = $track['Kind'];
		}

		$TotalTime = '';
		if (array_key_exists('TotalTime',$track)){
			$TotalTime = $track['TotalTime'];
		}

		if($checkedTrack){
			if($checkedTrack[0]['Name'] != $Name ||
			$checkedTrack[0]['Artist'] != $Artist ||
			$checkedTrack[0]['Genre'] != $Genre ||
			$checkedTrack[0]['Kind'] != $Kind ||
			$checkedTrack[0]['TotalTime'] != $TotalTime){

				$updatedTrack = "UPDATE tracks set Name = ?, Artist = ?, Genre = ?, Kind = ?, TotalTime = ? where PersistentID = ?";
				$result = $sqlDb->dbcon->prepare($updatedTrack);
				$result->execute(array($Name, $Artist, $Genre, $Kind, $TotalTime, $checkedTrack[0]['PersistentID']));
			}
		}else{
			$newInsert = " INSERT into tracks (PersistentID, Name, Artist, Genre, Kind, TotalTime) values (?, ?, ?, ?, ?, ?)";
			$result = $sqlDb->dbcon->prepare($newInsert);
			$result->execute(array($track['PersistentID'], $Name, $Artist, $Genre, $Kind, $TotalTime));
		}
	}

	/* 	Get all MySQL Tracks
		Loop through MySQL tracks, checking if the record exists in Mongo.
		If the track (persistentId) does not exist in Mongo, insert the record.
	*/	
	$sqlQuery = " SELECT * from tracks";
	$result = $sqlDb->dbcon->prepare($sqlQuery);
	$result->execute();
	$sqlTracks = $result->fetchAll();
	
	foreach($sqlTracks as $sqlTrack){
		$mongoTracks = $itunes->find(array('PersistentID' => $sqlTrack['PersistentID']));
		if($mongoTracks->count() == 0){
			$obj = array(
				"PersistentID"=>$sqlTrack['PersistentID'],
				"type"=>"track",
				"Name"=>$sqlTrack['Name'],
				"Artist"=>$sqlTrack['Artist'],
				"Genre"=>$sqlTrack['Genre'],
				"Kind"=>$sqlTrack['Kind'],
				"TotalTime"=>$sqlTrack['TotalTime']
			);
			$itunes->save($obj);
			//$itunes->insert($obj, array("safe" => true));
		}
	}
echo "We're good! All Done.";
?>