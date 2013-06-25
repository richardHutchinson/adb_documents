<?
	session_start();
	if(!isset($_SESSION["currentPage"])){
		$_SESSION["currentPage"]=1;
	}
	ini_set("mongo.cmd", ":");
	$mongo = new Mongo();
	$itunes = $mongo->selectDb('itunes')->selectCollection('itunes');
	$numPages = ceil($itunes->find(array('type' => 'track'))->count() / 10);
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/main.css" type="text/css">
</head>
<body>
	<div id="wrapper">
		<span id="page">Page<? if(isset($_SESSION['currentPage'])){echo $_SESSION["currentPage"];} ?></span>
		<a href="" data-page="0" class="startOver">(go to 1)</a>
		<div id="pagination">
			<a href="" data-page="<? echo $_SESSION["currentPage"] ?>" class="prevTen">prev</a>
			<a href="" data-page="<? echo $_SESSION["currentPage"] ?>" class="nextTen">next</a>
		</div>
		<table cellpadding="5">
			<tr class="headings">
				<th>Track Name</th>
				<th>Artist</th>
				<th>Genre</th>
				<th>Track Length</th>
				<th>Kind</th>
				<th>DELETE</th>
			</tr>
		</table>
		<a href="newTrack.php">Make New Track!</a>
	</div>
<input type="hidden" id="numPages" value="<? echo $numPages ?>"/>
<input type="hidden" id="sessionPage" value="<? echo $_SESSION['currentPage'] ?>"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
	var convertMS = function(ms) {
		var s = (parseInt(ms / 1000) % 60),
			seconds = (s>9) ? s : '0'+s;
		var m = (parseInt(ms / 1000 / 60) % 60),
			minutes = (m>9) ? m : '0'+m;
		var h = (parseInt( ms / 1000 / 3600) % 24),
			hours = (h>0) ? '0:' + h : '';
		return hours+minutes+':'+seconds;
	}
	var loadTracks = function(pg){
		$('#page').html('Page '+pg);
		$.ajax({
			url: 'listTracks.php',
			type: 'get',
			data: {
				page: pg
			},
			dataType: 'json',
			success: function(response){
				var trackData = '';
				$.each(response.tracks, function(){
					var track = this,
						artist = track.Artist,
						name = track.Name,
						genre = track.Genre,
						kind = track.Kind,
						persistentId = track.PersistentID,
						totalTime = convertMS(track.TotalTime);

					trackData += '<tr class="tInfo"><td><a href="trackDetail.php?pId='+persistentId+'" class="edit" data-id="'+persistentId+'">'+name+'</a></td><td>'+artist+'</td><td>'+genre+'</td><td>'+totalTime+'</td><td>'+kind+'</td><td><a href="deleteTrack.php?pId='+persistentId+'">delete</a></td></tr>';
				});
				$('.headings').after(trackData);
			}
		});
		$('.nextTen').attr('data-page', pg);
		$('.prevTen').attr('data-page', pg);
	};

	loadTracks($('#sessionPage').val());
});
</script>
</body>
</html>