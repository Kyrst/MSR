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
		User::addSong(1, 'Notize - Merula', true);
		User::addSong(1, 'Loudeast - Lights Off Quille Quero', true);
		User::addSong(1, 'Robert Babicz Centurian', true);
		User::addSong(1, 'Henry Saiz - Love Mythology (Juan Deminicis Dub Mix)', true);
		User::addSong(1, 'Indiana - Only The Lonely (KANT Remix)', true);
		User::addSong(1, 'Eleven - 1r', true);
		User::addSong(1, 'P.SUS - What We Feel', true);
		User::addSong(1, 'Maya Jane Coles - Watcher (Burnski Remix)', true);
		User::addSong(1, 'Robert Carty - In Jupitor\'s Light', true);
		User::addSong(1, 'Bing Satellites - Above The Clouds', true);
		User::addSong(1, 'Pherotone - Look Into Your Eyes', true);
		User::addSong(1, 'Metropolitan Poets - Eternal', true);
		User::addSong(1, 'Metronomy - Love Letters (Crom & Thanh Remix)', true);
		User::addSong(1, 'Indian Summer - Loveweights (Worthy Remix)', true);
		User::addSong(1, 'Anja Schneider - Dubmission (Lee Van Dowski Remix)', true);
		User::addSong(1, 'Jacques Bon - Ciel De Nuit', true);
		User::addSong(1, 'Dauwd - Jupiter George', true);
		User::addSong(1, 'Pretty Pink - What Is Love', true);
		User::addSong(1, 'Cucumbers - Just Believe Me', true);
	}
}