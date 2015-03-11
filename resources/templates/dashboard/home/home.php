<div class="ui raised segment" style="padding-bottom:22px">
	<h1>Dashboard</h1>

	<div class="row">
		<div class="four wide column">
			<h2 style="margin:0 0 8px">Your Songs</h2>
		</div>

		<div class="twelve wide column">
			<form action="<?= URL::route('add-song') ?>" method="post" id="add_song_form" class="ui small form segment teal">
				<div class="field">
					<label>Add Song</label>
					<input type="text" name="title" id="add_song_title" placeholder="Title">
				</div>

				<button type="submit" name="add_song" id="add_song_button" class="mini ui submit button">Add</button>
			</form>
		</div>
	</div>

	<?= \App\Helpers\Core\Markup::segmentLoadingContainer('songs_container', NULL, 'purple') ?>

	<?php /*<?= \App\Helpers\Core\Markup::segmentLoadingContainer('found_songs_container', NULL) ?>*/ ?>
</div>