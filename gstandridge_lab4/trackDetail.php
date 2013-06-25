<?
session_start();
ini_set("mongo.cmd", ":");
$mongo = new Mongo();
$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');

if(empty($_GET["pId"])){
	echo json_encode(array("unsuccessful"=>0));
}elseif(!empty($_GET["pId"])){
	$track = $itunes->findOne(array('PersistentID' => $_GET["pId"]));
}?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/main.css" type="text/css">
</head>
<body>
	<div id="wrapper">
		<form action="updateTrack.php" method="post">
			<p><label for="track_title">Track Title</label><input type="text" name="track_title" value="<? echo $track['Name']; ?>" id="track_title"><p>
			<p><label for="track_artist">Artist Name</label><input type="text" name="track_artist" value="<? echo $track['Artist']; ?>" id="track_artist"><p>
			<p><label for="track_genre">Track Genre</label><input type="text" name="track_genre" value="<? echo $track['Genre']; ?>" id="track_genre"><p>
			<p><label for="track_kind">File Type</label><input type="text" name="track_kind" value="<? echo $track['Kind']; ?>" id="track_kind"><p>
			<input type="hidden" name="track_pId" value="<? echo $_GET["pId"]; ?>" id="track_pId">
			<p><input type="submit" value="Update"></p>
		</form>
		<a href="index.php">Go home!</a>
	</div>
<input type="hidden" id="sessionPage" value="<? echo $_SESSION['currentPage'] ?>"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>