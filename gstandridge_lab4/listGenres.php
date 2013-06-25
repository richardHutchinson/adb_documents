<?
	ini_set("mongo.cmd", ":");
	$mongo = new Mongo();
	$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');
	$genres = $itunes->find(array('type'=>'track'))->sort(array('Genre'=>1));

	$jsonArray = array();

	$counter = 0;
	$prevRecord = '';
	foreach($genres as $json){
	$counter++;

		if (array_key_exists('Genre',$json)){
			if($prevRecord != $json['Genre']){
				$jsonArray[$counter] = array('Genre'=>$json['Genre']);
				$prevRecord = $json['Genre'];
			}
		}

	}
	echo json_encode($jsonArray);
?>