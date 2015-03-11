<?php namespace App\Models;

use Carbon\Carbon;

class User_Song_Spotify_Track extends \Illuminate\Database\Eloquent\Model
{
	public $table = 'user_song_spotify_tracks';

	public function spotifyTrack()
	{
		return $this->belongsTo('\App\Models\Spotify_Track', 'spotify_track_id', 'id');
	}

	public function user()
	{
		return $this->belongsTo('\App\Models\User', 'user_id', 'id');
	}

	public function dateFound()
	{
		$date_found_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $this->spotifyTrack->created_at);

		return $date_found_datetime;
	}
}