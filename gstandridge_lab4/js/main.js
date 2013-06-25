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
//ON PAGE LOAD
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

//NEXT PAGE BUTTON
var numPages = $('#numPages').val();
	$('.nextTen').click(function(){
		$('.tInfo').remove();

		var i = $(this).attr('data-page');

		if(i<numPages){
			i++;
		}else{
			i=1;
		}
		loadTracks(i);
		return false;
	});
	$('.prevTen').click(function(){
		$('.tInfo').remove();

		var i = $(this).attr('data-page');

		if(i>1){
			i--;
		}else{
			i=numPages;
		}
		loadTracks(i);
		return false;
	});
	$('.startOver').click(function(){
		$('.tInfo').remove();
		var i = 1;
		loadTracks(i);
		return false;
	});

//MORE FUNCTIONALITY
	$(window).on('click', '.edit', function(){
		var pId = $(this).attr('data-id');
		$.ajax({
			url: 'listTrack.php',
			type: 'get',
			data: {
				pId: pId
			},
			dataType: 'json',
			success: function(response){
				alert('yay!!!');
			}
		});
	});
});