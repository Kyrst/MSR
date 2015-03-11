<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
	public function run()
	{
		Model::unguard();

		$this->call('UserTableSeeder');
		$this->call('UserSongTableSeeder');
	}
}

class UserTableSeeder extends Seeder
{
	public function run()
	{
		User::register('dennis@myspotifyreminder.com', 'dennis', 'Dennis', 'Nygren');
	}
}

class UserSongTableSeeder extends Seeder
{
	public function run()
	{
		User::addSong(1, 'Ellin Spring - Pretty Girl', true);
		User::addSong(1, 'Notize - Merula', true);
		User::addSong(1, 'Loudeast - Lights Off Quille Quero', true);
		User::addSong(1, 'Robert Babicz Centurian', true);
		User::addSong(1, 'Mot1v - My Winter', true);
		User::addSong(1, 'Helly Larson - Silence', true);
		User::addSogn(1, 'Henry Saiz - Love Mythology (Juan Deminicis Dub Mix)', true);
		User::addSong(1, 'Luis Junior - Sesto-Senso', true);
		User::addSong(1, 'Indiana - Only The Lonely (KANT Remix)', true);
		User::addSong(1, 'Low Steppa - Outro', true);
		User::addSong(1, 'Eleven - 1r', true);
		User::addSong(1, 'P.SUS - What We Feel', true);
		User::addSong(1, 'Maya Jane Coles - Watcher (Burnski Remix)', true);
		User::addSong(1, 'Robert Carty - In Jupitor\'s Light', true);
		User::addSong(1, 'Bing Satellites - Above The Clouds', true);
		User::addSong(1, 'Pherotone - Look Into Your Eyes', true);
		User::addSong(1, 'Metropolitan Poets - Eternal', true);
	}
}