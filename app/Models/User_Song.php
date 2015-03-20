<?php namespace App\Models;

use Illuminate\Support\Str;
use SpotifyWebAPI\SpotifyWebAPI;

class User_Song extends \Illuminate\Database\Eloquent\Model
{
	public $table = 'user_songs';

	public function spotifyTracks()
	{
		$user_song_spotify_tracks = Spotify_Track::join('user_song_spotify_tracks', 'user_song_spotify_tracks.spotify_track_id', '=', 'spotify_tracks.id')
			->where('user_song_spotify_tracks.user_song_id', $this->id)
			->get();

		return $user_song_spotify_tracks;
	}

	public function delete()
	{
		parent::delete();

		// Delete spotify tracks without connection
		Spotify_Track::deleteWithoutConnection();
	}

	public function getAddedUnix()
	{
		return strtotime($this->created_at);
	}

	public function updateTracksOnSpotify()
	{
		$this->last_checked = date('Y-m-d H:i:s');
		$this->num_checks++;
		$this->save();

		$api = new SpotifyWebAPI();

		$minified_title = str_replace
		(
			[' - ', ' — ', '(', ')', ',', '&', '\"', '[', ']', '|'],
			' ',
			$this->title
		);

		$minified_title = trim(str_replace('  ', ' ', $minified_title));

		error_log($minified_title);

		$tracks_result = $api->search($minified_title, 'track');

		$tracks = $tracks_result->tracks->items;
		$num_tracks = count($tracks);

		if ( $num_tracks === 0 )
		{
			return [];
		}

		$tracks_result_formatted = \App\Helpers\Spotify::formatTracksResult($tracks_result);

		$updated_tracks = [];

		foreach ( $tracks_result_formatted as $track )
		{
			$spotify_album = Spotify_Album::where('spotify_id', $track['album']['id'])->first();

			if ( !$spotify_album )
			{
				$spotify_album = new Spotify_Album();
				$spotify_album->spotify_id = $track['album']['id'];
			}

			$spotify_album->name = $track['album']['name'];
			$spotify_album->spotify_link = $track['album']['spotify_link'];
			$spotify_album->web_link = $track['album']['web_link'];
			$spotify_album->uri = $track['album']['uri'];
			$spotify_album->save();

			$spotify_track = Spotify_Track::where('spotify_id', $track['id'])->first();

			if ( !$spotify_track )
			{
				$spotify_track = new Spotify_Track();
				$spotify_track->spotify_id = $track['id'];
			}

			foreach ( $track['artists'] as $artist )
			{
				$spotify_artist = Spotify_Artist::where('spotify_id', $artist['id'])->first();

				if ( !$spotify_artist )
				{
					$spotify_artist = new Spotify_Artist();
					$spotify_artist->spotify_id = $artist['id'];
				}

				$spotify_artist->name = $artist['name'];
				$spotify_artist->spotify_link = $artist['spotify_link'];
				$spotify_artist->uri = $artist['uri'];
				$spotify_artist->web_link = $artist['web_link'];
				$spotify_artist->save();
			}

			$spotify_track->name = $track['name'];
			$spotify_track->slug = Str::slug($track['name']);
			$spotify_track->album_id = $spotify_album->id;
			$spotify_track->duration = $track['duration'];
			$spotify_track->isrc = $track['isrc'];
			$spotify_track->preview_url = $track['preview_url'];
			$spotify_track->spotify_link = $track['spotify_link'];
			$spotify_track->uri = $track['uri'];
			$spotify_track->web_link = $track['web_link'];
			$spotify_track->save();

			$user_song_spotify_track = User_Song_Spotify_Track::where('user_song_id', $this->id)
				->where('spotify_track_id', $spotify_track->id)
				->first();

			if ( !$user_song_spotify_track )
			{
				$user_song_spotify_track = new User_Song_Spotify_Track();
				$user_song_spotify_track->user_song_id = $this->id;
				$user_song_spotify_track->spotify_track_id = $spotify_track->id;
			}

			$user_song_spotify_track->save();

			$updated_tracks[] = $spotify_track->toArray();
		}

		return $updated_tracks;
	}

	public function getLastCheckedUnix()
	{
		return strtotime($this->last_checked);
	}
}