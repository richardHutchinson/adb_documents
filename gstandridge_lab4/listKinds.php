<?
	ini_set("mongo.cmd", ":");
	$mongo = new Mongo();
	$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');
	$kinds = $itunes->find(array('type'=>'track'))->sort(array('Kind'=>1));

	$jsonArray = array();

	$counter = 0;
	$prevRecord = '';
	foreach($kinds as $json){
	$counter++;

		if (array_key_exists('Kind',$json)){
			if($prevRecord != $json['Kind']){
				$jsonArray[$counter] = array('Kind'=>$json['Kind']);
				$prevRecord = $json['Kind'];
			}
		}

	}
	echo json_encode($jsonArray);
?>