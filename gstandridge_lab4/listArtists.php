<?
	ini_set("mongo.cmd", ":");
	$mongo = new Mongo();
	$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');
	$artists = $itunes->find(array('type'=>'track'))->sort(array('Artist'=>1));

	$jsonArray = array();

	$counter = 0;
	$prevRecord = '';
	foreach($artists as $json){
	$counter++;

		if (array_key_exists('Artist',$json)){
			if($prevRecord != $json['Artist']){
				$jsonArray[$counter] = array('Artist'=>$json['Artist']);
				$prevRecord = $json['Artist'];
			}
		}

	}
	echo json_encode($jsonArray);
?>