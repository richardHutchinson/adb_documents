<?
ini_set("mongo.cmd", ":");
$mongo = new Mongo();
$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/main.css" type="text/css">
</head>
<body>
	<div id="wrapper">
		<h1>MAKE NEW TRACK</h1>
		<form action="insertTrack.php" method="post">
			<p><label for="new_title">Track Title</label><input type="text" name="new_title" id="new_title"><p>
			<p><label for="new_artist">Artist Name</label><input type="text" name="new_artist" id="new_artist"><p>
			<p><label for="new_genre">Track Genre</label><input type="text" name="new_genre" id="new_genre"><p>
			<p><label for="new_kind">File Type</label><input type="text" name="new_kind" id="new_kind"><p>
			<p><label for="new_kind">Total Time</label><input type="text" name="new_TotalTime" placeholder="(in milliseconds)" id="new_TotalTime"><p>
			<p><input type="submit" value="Insert"></p>
		</form>
		<a href="index.php">Go home!</a>
	</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>