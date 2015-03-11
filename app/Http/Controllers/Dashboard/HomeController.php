<?php namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\User_Song;
use App\Models\User_Song_Spotify_Track;

class HomeController extends \App\Http\Controllers\DashboardController
{
	public function home()
	{
		return $this->display();
	}

	public function addSongPost()
	{
		$title = trim(\Input::get('title'));

		try
		{
			$user_song = User::addSong($this->user->id, $title);
		}
		catch ( \Exception $e )
		{
			$this->ajax->outputWithError($e->getMessage());
		}

		$updated_tracks = $user_song->updateTracksOnSpotify();

		$this->ajax->addData('added_song_title', $title);
		$this->ajax->addData('updated_tracks', $updated_tracks);

		return $this->ajax->output();
	}

	public function deleteSongPost()
	{
		$id = \Input::get('id');

		$user_song = User_Song::where('id', $id)->first();

		if ( $user_song === null )
		{
			return $this->ajax->outputWithError('Could not find song with ID "' . $id . '".');
		}

		$user_song->delete();

		return $this->ajax->output();
	}

	public function getSongs()
	{
		$songs = $this->user->getNonFoundSongs();
		$num_songs = count($songs);

		$view = view('dashboard/home/partials/songs_container');
		$view->num_songs = $num_songs;
		$view->songs = $songs;

		$this->ajax->addData('num_songs', $num_songs);
		$this->ajax->addData('html', $view->render());

		return $this->ajax->output();
	}

	public function update()
	{
		$results = [];

		foreach ( $this->user->songs as $user_song )
		{
			$results[] = $user_song->updateTracksOnSpotify();
		}

		// Have fun with $results...

		//$this->ajax->addData('results', $results);

		return $this->ajax->output();
	}

	public function thisIsItPost($song_slug)
	{
		$user_song_id = \Input::get('user_song_id');
		$spotify_track_id = \Input::get('spotify_track_id');

		$user_song_spotify_track = User_Song_Spotify_Track::where('user_song_id', $user_song_id)
			->where('spotify_track_id', $spotify_track_id)
			->first();

		if ( !$user_song_spotify_track )
		{
			return $this->ajax->outputWithError('Could not find user song with id "' . $spotify_track_id . '".');
		}

		//$user_song_spotify_track->this_is_it = 'yes';
		//$user_song_spotify_track->save();

		\DB::update('UPDATE user_song_spotify_tracks SET this_is_it = "yes", updated_at = "' . date('Y-m-d H:i:s') . '" WHERE user_song_id = ? AND spotify_track_id = ?', [$this->user->id, $spotify_track_id]);
		//\DB::update('UPDATE user_songs SET found = "yes" WHERE id =

		return $this->ajax->output();
	}
}