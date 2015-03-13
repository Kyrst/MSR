var $add_song_title = $('#add_song_title');

var $add_song_button = $('#add_song_button'),
	add_song_button_default_text = $add_song_button.text();

var players = [],
	current_player = null,
	$player_play_button = $('#player_play_button');

var PAUSE_BUTTON_HTML = '<i class="pause icon"></i>',
	PLAY_BUTTON_HTML = '<i class="play icon"></i>';

buzz.defaults.loop = $('#player_loop_toggle').is(':checked');

$('#add_song_form').on('submit', function()
{
	var title = $add_song_title.val();

	if ( title === '' )
	{
		$add_song_title.focus();

		return false;
	}

	$add_song_button.prop('disabled', true).text('Adding...');

	$core.ajax.post
	(
		$core.uri.urlize('add-song'),
		{
			title: title
		},
		{
			success: function(result)
			{
				if ( result.error === null )
				{
					$add_song_title.val('').focus();

					refresh_songs();

					var num_updated_tracks = result.data.updated_tracks.length;

					if ( num_updated_tracks > 0 )
					{
						$core.ui.message.success('Song "' + result.data.added_song_title + '" added and found ' + num_updated_tracks + ' track' + (num_updated_tracks !== 1 ? 's' : '') + ' on Spotify.');
					}
					else
					{
						$core.ui.message.success('Song "' + result.data.added_song_title + '" added, but didn\'t find on Spotify.');
					}
				}
				else
				{
					$core.ui.message.warning(result.error);
				}
			},
			error: function()
			{
				$core.ui.message.error('Something went wrong. Please try again!');
			},
			always: function()
			{
				$add_song_button.text(add_song_button_default_text).prop('disabled', false);
			}
		}
	);

	return false;
});

function refresh_songs()
{
	$songs_container.css(
	{
		'height': $songs_container.height()
	});

	$songs_container.html(songs_container_loading_html).removeClass('no-data').addClass('is-loading');

	$core.ajax.get
	(
		$core.uri.urlize('get-songs'),
		{
		},
		{
			success: function(result)
			{
				$songs_container.html(result.data.html).removeClass('is-loading');

				if ( result.data.num_songs === 0 )
				{
					$songs_container.addClass('no-data');
				}

				$songs_container.css(
				{
					'height': 'auto'
				});

				$songs_container.find('.delete-song-button').on('click', function()
				{
					var $delete_button = $(this),
						$song = $delete_button.closest('.song'),
						song_id = $song.data('id'),
						song_title = $song.data('title');

					$core.ui.message.setEngine(new Core_UI_Message_Engine_SweetAlert());
					$core.ui.message.confirm('Are you sure you want to delete song "' + song_title + '"?', function()
					{
						$delete_button.prop('disabled', true).text('Deleting...');

						$core.ajax.post
						(
							$core.uri.urlize('delete-song'),
							{
								id: song_id
							},
							{
								success: function(result)
								{
									//$song.fadeOut('fast', function()
									//{
										$core.ui.message.success('Song "' + song_title + '" was deleted.');

										refresh_songs();
									//});
								},
								error: function()
								{
									$delete_button.text('Delete').prop('disabled', false);

									$core.ui.message.error('Could not delete song right now.');
								}
							}
						);
					});
					$core.ui.message.setEngine(new Core_UI_Message_Engine_Noty());
				});

				$songs_container.find('.song').each(function(song_index, song_element)
				{
					var $song = $(song_element),
						song_id = $song.data('id');

					var sounds = [];

					$song.find('.preview-player').each(function(preview_player_index, preview_player_element)
					{
						var $preview_player = $(preview_player_element),
							$spotify_track = $preview_player.closest('.spotify-track'),
							spotify_track_id = $spotify_track.data('id');

						players[spotify_track_id] = new buzz.sound($preview_player.data('url'));
						players[spotify_track_id].load();

						$preview_player.off('click').on('click', function()
						{
							var $spotify_track = $(this).closest('.spotify-track'),
								spotify_track_id = $spotify_track.data('id');

							if ( current_player !== null && current_player.spotify_track_id === spotify_track_id )
							{
								console.log('1');
								if ( current_player.player.isPaused() )
								{
									resume_player();
								}
								else
								{
									pause_player();
								}
							}
							else
							{
								console.log('2');
								play_track(spotify_track_id);
							}
						});
					});
				});

				$songs_container.find('.this-is-it-button').on('click', function(e)
				{
					e.preventDefault();

					var $this_is_it_button = $(this),
						$spotify_track = $this_is_it_button.closest('.spotify-track'),
						spotify_track_id = $spotify_track.data('id'),
						spotify_track_title = $spotify_track.data('title'),
						user_song_id = $spotify_track.data('user_song_id');

					$core.ui.message.setEngine(new Core_UI_Message_Engine_SweetAlert());

					$core.ui.message.confirm('Are you sure you want to mark "' + spotify_track_title + '" as "This Is It"?', function()
					{
						$this_is_it_button.addClass('loading');

						$core.ajax.post
						(
							$this_is_it_button.attr('href'),
							{
								spotify_track_id: spotify_track_id,
								user_song_id: user_song_id
							},
							{
								success: function(result)
								{
									var $song = $spotify_track.closest('.song');

									$song.addClass('animated flipOutX');
									$song.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function()
									{
										$song.remove();
									});
								},
								error: function()
								{
									$core.ui.message.error('Something went wrong! Could not mark as \'this is it\' right now.');
								},
								always: function()
								{
									$this_is_it_button.removeClass('loading');
								}
							}
						);
					});
				});

				$core.ui.message.setEngine(new Core_UI_Message_Engine_Noty());
			},
			error: function()
			{
			}
		}
	);
}

refresh_songs();

function play_track(spotify_track_id)
{
	var was_player_open = (current_player !== null);

	if ( current_player !== null )
	{
		pause_player();
	}

	var $preview_player = $('#preview_player_' + spotify_track_id),
		$spotify_track = $preview_player.closest('.spotify-track'),
		title = $spotify_track.data('title');

	// Stop player if already playing a track
	if ( current_player !== null )
	{
		current_player.player.stop();
	}

	var new_player = players[spotify_track_id];

	current_player =
	{
		player: new_player,
		spotify_track_id: spotify_track_id
	};

	// Buffering progress
	new_player.bind('progress', function(e)
	{
		//var percent = buzz.toPercent(this.getTime(), this.getDuration());
		//console.log(percent);
		//$('#player_progress').val(percent);

		console.log('loading media...');
	});

	new_player.bind('waiting', function(e)
	{
		console.log('waiting for media...');
	});

	new_player.bind('timeupdate', function(e)
	{
		var time = this.getTime(),
			duration = this.getDuration();

		if ( duration === '--' )
		{
			return;
		}

		var percent = buzz.toPercent(time, duration);
		$('#player_progress').val(percent);

		$('#player_position').html(buzz.toTimer(time));
		$('#player_duration').html(buzz.toTimer(duration));
	});

	new_player.bind('error', function(e)
	{
		alert('Player error');
		console.log(e);
	});

	new_player.bind('loadstart', function()
	{
		console.log('start loading of player');
	});

	new_player.bind('abort', function()
	{
		console.log('abort player');
	});

	new_player.bind('canplay', function()
	{
		console.log('canplay player');
	});

	new_player.bind('canplaythrough', function()
	{
		console.log('canplaythrough player');
	});

	new_player.bind('dataunavailable', function()
	{
		console.log('dataunavailable player');
	});

	new_player.bind('seeked', function()
	{
		console.log('seeked player');
	});

	new_player.bind('seeking', function()
	{
		console.log('seeking player');
	});

	$preview_player.html(PAUSE_BUTTON_HTML);
	$player_play_button.html(PAUSE_BUTTON_HTML);

	$('#player_title').html(title);

	// Start playing new track
	//new_player.play();

	if ( was_player_open === false )
	{
		show_player();
	}
}

function pause_player()
{
	if ( current_player === null )
	{
		return;
	}

	var $preview_player = $('#preview_player_' + current_player.spotify_track_id);

	current_player.player.pause();

	$preview_player.add($player_play_button).html(PLAY_BUTTON_HTML);
}

function resume_player()
{
	if ( current_player === null )
	{
		return;
	}

	var $preview_player = $('#preview_player_' + current_player.spotify_track_id);

	current_player.player.play();

	$preview_player.add($player_play_button).html(PAUSE_BUTTON_HTML);
}

function show_player()
{
	$('#player_container').addClass('show');
}

function hide_player()
{
	$('#player_container').removeClass('show');
}

$('#player_progress').on('click', function(e)
{
	var $player_progress = $(this),
		posX = $player_progress.offset().left,
		mouse_x_position = (e.pageX - posX),
		progress_bar_width = $player_progress.width(),
		duration = current_player.player.getDuration(),
		x_percent = (mouse_x_position / progress_bar_width) * 100;

	var new_time = buzz.fromPercent(x_percent, duration);

	current_player.player.setTime(new_time);
});

$player_play_button.on('click', function()
{
	if ( current_player === null )
	{
		return;
	}

	if ( current_player.player.isPaused() )
	{
		resume_player();
	}
	else
	{
		pause_player();
	}
});