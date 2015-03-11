<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpotifyTracksTable extends Migration
{
	public function up()
	{
		Schema::create('spotify_albums', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('spotify_id', 100)->unique();
			$table->string('name', 300);
			$table->binary('images')->nullable();
			$table->string('spotify_link', 300);
			$table->string('web_link', 300);
			$table->string('uri', 300);
			$table->timestamps();
		});

		Schema::create('spotify_tracks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('spotify_id', 100)->unique();
			$table->string('name', 300);
			$table->string('slug', 300);
			$table->integer('album_id')->unsigned();
			$table->foreign('album_id')->references('id')->on('spotify_albums');
			$table->integer('duration')->unsigned();
			$table->char('isrc', 12);
			$table->string('preview_url', 300)->nullable();
			$table->string('spotify_link', 300);
			$table->string('web_link', 300);
			$table->string('uri', 300);
			$table->timestamps();
		});

		Schema::create('user_song_spotify_tracks', function(Blueprint $table)
		{
			$table->integer('user_song_id')->unsigned();
			$table->foreign('user_song_id')->references('id')->on('user_songs');//->onDelete('cascade');
			$table->integer('spotify_track_id')->unsigned();
			$table->foreign('spotify_track_id')->references('id')->on('spotify_tracks');
			$table->enum('this_is_it', ['yes', 'no'])->default('no');
			$table->timestamps();
		});

		Schema::create('spotify_artists', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('spotify_id', 100)->unique();
			$table->string('name', 300);
			$table->string('spotify_link', 300);
			$table->string('web_link', 300);
			$table->string('uri', 300);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('spotify_albums');
		Schema::drop('spotify_artists');
		Schema::drop('user_song_spotify_tracks');
		Schema::drop('spotify_tracks');
	}
}