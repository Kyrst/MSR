<div id="sign_in_container" class="ui segment">
	<h1>Sign In</h1>

	<form id="sign_in_form" action="<?= URL::current() ?>" method="post" class="ui warning form">
		<div id="email_field" class="field">
			<label>Email</label>
			<input type="text" name="email" id="email">
		</div>

		<div id="password_field" class="field">
			<label>Password</label>
			<input type="password" name="password" id="password">
		</div>

		<button type="submit" id="sign_in_button" class="ui submit button">Sign In</button>
	</form>
</div>