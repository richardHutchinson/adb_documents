<?php

// ------------------------ PHP VAR DUMP ----------------------//
require_once 'CFDump.php';
$mongo = new Mongo();
$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');
echo 'Document count: ' . $itunes->count();

// ------------------------ Get MYSQL DATA ----------------------//
function getTrackOnSql($persistendId=""){
	$db = new \PDO("mysql:hostname=127.0.0.1;port=3306", "root","root");
	
	$q = "select * from ADB.itunes where persistentId= :id";
	
	$st = $db->prepare($q); 	
	$st->execute( array(":id"=>$persistendId) );
	$res = $st->fetchAll();	
	
	return $res;
}

// ------------------------ INSERT MYSQL DATA ----------------------//
function insertTrack($persistentId, $album, $artist, $size, $year, $playCount, $updateDate){
	
	echo 'persistentId'.$persistentId.'<br>';
	echo 'album'.$album.'<br>';
	echo 'artist'.$artist.'<br>';
	echo $size.'<br>';
	echo 'year'.$year.'<br>';
	echo 'playCount'.$playCount.'<br>';
	echo 'updateDate'.$updateDate.'<br>';

	$db = new \PDO("mysql:hostname=127.0.0.1;port=3306", "root","root");
	
	$album = str_replace("'"," ",$album);
	$artist = str_replace("'"," ",$artist);
	
	$q = 	"insert into ADB.itunes (
				persistentId, 
				album, 
				artist, 
				size, 
				year, 
				playCount, 
				updateDate
			) values (
				'".$persistentId."', 
				'".$album."', 
				'".$artist."', 
				'".$size."', 
				'".$year."', 
				'".$playCount."', 
				'".$updateDate."'
			)";
	
	$st = $db->prepare($q); 	
	$st->execute();
	$res = $st->fetchAll();	
	
	return $res;
}//close new track

// ------------------------ UPDATE MYSQL DATA ----------------------//
function updateTrack($persistentId, $album, $artist, $size, $year, $playCount, $updateDate){
	
	$db = new \PDO("mysql:hostname=127.0.0.1;port=3306", "root","root");
	
	$q = 	"update ADB.itunes set
				persistentId = '".$persistentId."', 
				album = '".$album.", 
				artist = '".$artist."', 
				size = '".$size."', 
				year = '".$year.", 
				playCount = '".$playCount."', 
				updateDate = '".$updateDate."'
			where(
				persistentId = '".$persistentId."'
			)";
	
	$st = $db->prepare($q); 	
	$st->execute();
	$res = $st->fetchAll();	
	
	return $res;
}//close update track


// ------------------------ DISPLAY MONGO DATA ----------------------//
ini_set("mongo.cmd", ":");
$mongo = new Mongo();
$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');
//$MongoPersistentID = $itunes->find('PersistentID');
$shortTracks = $itunes->find(array('TotalTime' => array(':gt' => 0)));

// ----------------- LOOPING THROUGH MONGO DATA ------------------//
echo '<ol>';
foreach ($shortTracks as $mongoTrack) {
		/*echo '<li>'. 
				'Album:' . $mongoTrack['Album'].
				'<b> ( </b>' . $mongoTrack['PersistentID'].'<b> ) </b>'.
				'</li>';
		*/	
		$sqlTrack = getTrackOnSql($mongoTrack['PersistentID']);
		//echo 'Id:'.$mongoTrack['PersistentID'].'<br>';
		//echo 'Mongo Date:'.$mongoTrack['updateDate'].'-<br>';
		//echo 'SQL Date:'.$sqlTrack[0]['updateDate'].'-<br>';
		
		if(empty($sqlTrack)){
			echo '<li><b>Insert </b></b>';
			
			$mongoPlayCount = 0;
			if (array_key_exists('playCount',$mongoTrack)){
				$mongoPlayCount = $mongoTrack['playCount'];
			}
			
			$mongoUpdateDate = '';
			if (array_key_exists('updateDate',$mongoTrack)){
				$mongoUpdateDate = $mongoTrack['updateDate'];
			} 
			
			$Year = '';
			if (array_key_exists('Year',$mongoTrack)){
				$Year = $mongoTrack['Year'];
			} 
			
			$Size = '';
			if (array_key_exists('Size',$mongoTrack)){
				$Size = $mongoTrack['Size'];
			} 
			
			$Artist = '';
			if (array_key_exists('Artist',$mongoTrack)){
				$Artist = $mongoTrack['Artist'];
			}
			
			$Album = '';
			if (array_key_exists('Album',$mongoTrack)){
				$Album = $mongoTrack['Album'];
			} 
			
			echo '<br>mongoUpdateDate:'.$mongoUpdateDate.'<br>';
			
			insertTrack(
				$mongoTrack['PersistentID'], 
				$Album,
				$Artist,
				$Size,
				$Year,
				$mongoPlayCount,
				$mongoUpdateDate
			);			
			
		}else if(array_key_exists('updateDate',$mongoTrack) && $mongoTrack['updateDate'] > $sqlTrack[0]['updateDate']){
			// Update SQL
			
			echo '<li><b>Update SQL</b></b>';
			new CFDump($sqlTrack);
			//echo '<br>Mongo:'.$mongoTrack['updateDate'].'<br>';
			//echo 'SQL:'.$sqlTrack[0]['updateDate'].'<br>';
			
			
			$mongoPlayCount = 0;
			if (array_key_exists('PlayCount',$mongoTrack)){
				$mongoPlayCount = $mongoTrack['PlayCount'];
			}
			
			$mongoUpdateDate = '';
			if (array_key_exists('UpdateDate',$mongoTrack)){
				$mongoUpdateDate = $mongoTrack['UpdateDate'];
			} 
			
			updateTrack(
				$mongoTrack['PersistentID'], 
				$mongoTrack['Album'],
				$mongoTrack['Artist'],
				$mongoTrack['Size'],
				$mongoTrack['Year'],
				$mongoPlayCount,
				$mongoUpdateDate
			);//close update track function
			
		}else if(array_key_exists('updateDate',$mongoTrack) && $mongoTrack['updateDate'] < $sqlTrack[0]['updateDate']){
			// update Mongo
			echo '<li><b>Update Mongo</b></b>';
			new CFDump($sqlTrack);
			//echo '<br>Mongo:'.$mongoTrack['updateDate'].'<br>';
			//echo 'SQL:'.$sqlTrack[0]['updateDate'].'<br>';
			
			$obj = array(
				"_id"=>$mongoTrack['_id'],
				"PersistentID"=>$mongoTrack['PersistentID'],
				"Album"=>$sqlTrack[0]['album'],
				"Artist"=>$sqlTrack[0]['artist'],
				"Size"=>$sqlTrack[0]['size'],
				"Year"=>$sqlTrack[0]['year'],
				"playCount"=>$sqlTrack[0]['playCount'],
				"updateDate"=>$sqlTrack[0]['updateDate'],
				"TotalTime"=>$mongoTrack['TotalTime']
				);
			$itunes->save($obj); 
		} 
}
echo '</ol>';
echo 'The End!'

?>