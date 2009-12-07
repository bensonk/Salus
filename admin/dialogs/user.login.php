			<div id="header">
				<div id="title">
					<h1><? echo $shortTitle; ?> Administration - Home</h1>
				</div>
				<div id="account">

				</div>
				<div id="headerBottom"></div>
			</div>
			<div id="login">
				<div id="loginMessage"><? echo $message; ?></div>
				<form name="login" method="post" action="index.php">
					<fieldset>
						<label>Username</label>
						<input type="text" name="username" />
					</fieldset>
					
					<fieldset>
						<label>Password</label>
						<input type="password" name="password" />
					</fieldset>
					
					<fieldset id="submit">
						<input type="submit" value="Login &raquo;" id="submit" />
					</fieldset>
				</form>
				<div class="center" id="forgot">
					<a href="user.sendLogin.php">Forgot your username or password?</a>
				</div>
			</div>
			