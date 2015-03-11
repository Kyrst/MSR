	<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">

		<title><?= $page_title ?></title>

		<?php foreach ( $assets[\App\Http\Controllers\CoreController::ASSET_CSS] as $css_file ): ?>
			<link href="<?= ($css_file['external'] === FALSE ? $base_url : '') . $css_file['path'] ?>" rel="stylesheet">
		<?php endforeach ?>
	</head>

	<body id="<?= $page_id ?>">
		<?php if ( $current_page !== 'dashboard/auth/sign-in' ): ?>
			<div class="ui page grid">
				<header id="header" class="row">
					<div class="four wide column">
						<a href="<?= URL::route('home') ?>" id="logo" class="ui header">My Spotify Reminder</a>
					</div>

					<div class="twelve wide column" style="text-align:right">
						<div id="player_container">
							<a href="javascript:" id="player_play_button"><i class="play icon"></i></a>
							<span id="player_title"></span>
							<progress id="player_progress" max="100" value="0"></progress>
							<label><input type="checkbox" name="loop" id="player_loop_toggle" checked> Loop</label>
							<span id="player_position"></span> / <span id="player_duration"></span>
						</div>

						<div id="header_dropdown" class="ui selection dropdown">
							<i class="dropdown icon"></i>
							<div class="text"><?= $user->getName() ?></div>
							<div class="menu">
								<a href="<?= $user->getURL(\App\Models\User::PROFILE_PAGE) ?>" class="item">Profile</a>
								<a href="javascript:" id="update_button" class="item">Update</a>
								<a href="<?= URL::route('dashboard/sign-out') ?>" class="item">Sign Out</a>
							</div>
						</div>
					</div>
				</header>

				<div class="row">
					<div class="sixteen wide column">
						<?php if ( isset($breadcrumb) ): ?>
							<?= $breadcrumb ?>
						<?php endif ?>

						<?= $content ?>
					</div>
				</div>
			</div>
		<?php else: ?>
			<?= $content ?>
		<?php endif ?>

		<?= $jquery . $inline_js ?>

		<?php foreach ( $assets[\App\Http\Controllers\CoreController::ASSET_JS] as $js_file ): ?>
			<script src="<?= ($js_file['external'] === FALSE ? $base_url : '') . $js_file['path'] ?>"></script>
		<?php endforeach ?>
	</body>
</html>