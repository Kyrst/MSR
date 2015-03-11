<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSongsTable extends Migration
{
	public function up()
	{
		Schema::create('user_songs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('title', 300);
			$table->string('slug', 300);
			$table->dateTime('last_checked')->nullable();
			$table->integer('num_checks')->unsigned()->default(0);
			$table->enum('found', ['yes', 'no'])->default('no');
			$table->timestamps();

			//$table->unique(['user_id', 'title']);
		});
	}

	public function down()
	{
		Schema::drop('user_songs');
	}
}