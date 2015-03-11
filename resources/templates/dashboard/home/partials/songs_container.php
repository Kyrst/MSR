<?php if ( $num_songs > 0 ): ?>
	<div class="song">
		<?php foreach ( $songs as $song ): ?>
			<?php
			$spotify_tracks = $song->spotifyTracks();
			$num_spotify_tracks = $spotify_tracks->count();
			?>
			<div data-id="<?= $song->id ?>" data-title="<?= e($song->title) ?>" class="ui vertical segment song open">
				<div class="main">
					<div class="info">
						<span class="song-title"><?= e($song->title) ?></span>
						<span class="song-checked">Checked <time data-livestamp="<?= $song->getLastCheckedUnix() ?>"></time></span>
					</div>

					<div class="toolbar">
						<a href="javascript:" class="mini ui red button delete-song-button">Delete</a>
					</div>

					<?php if ( $num_spotify_tracks > 0 ): ?>
						<div class="ui mini purple label song-status">On Spotify (<?= $num_spotify_tracks ?>)</div>
					<?php else: ?>
						<div class="ui mini label song-status">Not on Spotify</div>
					<?php endif ?>
				</div>

				<?php if ( $num_spotify_tracks > 0 ): ?>
					<div id="song_spotify_tracks_<?= $song->id ?>" class="spotify-tracks clearfix">
						<?php foreach ( $spotify_tracks as $spotify_track ): ?>
							<div data-id="<?= $spotify_track->id ?>" data-title="<?= $spotify_track->name ?>" data-user_song_id="<?= $spotify_track->user_song_id ?>" class="spotify-track">
								<span id="preview_player_<?= $spotify_track->id ?>" data-url="<?= $spotify_track->preview_url ?>" class="preview-player"><i class="play icon"></i></span>

								<a href="<?= $spotify_track->uri ?>" class="spotify-track-name"><?= $spotify_track->name ?></a>

								<a href="<?= $spotify_track->getURL(\App\Models\Spotify_Track::URL_THIS_IS_IT) ?>" class="ui mini button this-is-it-button">This Is It</a>

								<?php /*<strong>Date Found:</strong> <?= $spotify_track->userSongSpotifyTrack->dateFound()->format('Y-m-d') ?>*/ ?>
							</div>
						<?php endforeach ?>
					</div>
				<?php endif ?>
			</div>
		<?php endforeach ?>
	</div>
<?php else: ?>
	<span class="no-segment-data">No songs.</span>
<?php endif ?>