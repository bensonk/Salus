<? include("../salus.php"); ?>
<script language="javascript" type="text/javascript">
	$(function() {
		var options = {target: '#tbMessage', before: fadeSaveIn, after: fadeSaveOut};
		$('#loginThickbox').ajaxForm(options);
		return false; 
		});
	
	function fadeSaveOut(){
		
	}

	function fadeSaveIn(){
		$("#tbMessage").html('<img src="imgs/loading_small.gif" alt="loading" /> <strong>Logging in...</strong>');
		
		}
</script>
<div id="dialog">
	<div id="tbMessage">
		<img src="imgs/icons/error.gif" alt="Error" />
		Your security authentication has expired. Please re-enter your username and password below to resume editing&ndash;your work has not been lost.
	</div>
	<form name="login" id="loginThickbox" method="post" action="handlers/user.authError.php">
		<fieldset>
			<label id="username">Username</label>
			<input type="text" name="username" />
		</fieldset>
				
		<fieldset>
			<label id="password">Password</label>
			<input type="password" name="password" />
		</fieldset>
					
		<fieldset id="submit">
			<input type="submit" value="Login" id="submit" />
		</fieldset>
	</form>
</div>
			