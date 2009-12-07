<?
include("salus.php");
$user = new User();
$user -> Get($_SESSION['userId']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title><? echo $shortTitle; ?> Administration - Reset Password</title>
		<link href="css/screen.css" rel="stylesheet" type="text/css" media="screen" />
		<script language="javascript" type="text/javascript" src="js/jquery-compressed.js"></script>
		<script language="javascript" type="text/javascript" src="js/salus.js"></script>						
		<script language="javascript" type="text/javascript" src="js/jquery.form.js"></script>		
		<script language="javascript" type="text/javascript">
		$(function() {		
        var options = {
			target:   '#output',
			before: fadeSaveIn
        };
        
        $('#resetPassword').ajaxForm(options);
           return false; 
		});
		
		function fadeSaveIn(){
			$("#output").html('<img src="imgs/loading_small.gif" alt="loading" /> Resetting password...');
			$("#output").fadeIn(500);
		}
		</script>
	</head>
	<body>
		<div id="container">
		<div id="header">
			<div id="title">
				<h1><a href="index.php"><? echo $shortTitle; ?> Administration</a> &raquo; Reset Password</h1>
			</div>
			<div id="account"></div>
			<div id="headerBottom"></div>
		</div>	
		<div id="oneColumn">
			<p>To reset your password, please enter your new password twice in the boxes below.</p><br />
			<div id="output"></div>	
			<form id="resetPassword" method="post" action="handlers/user.passwordReset.php">	
				<input type="hidden" value="<? echo $_GET['userId']; ?>" name="userId" />
				<input type="hidden" value="<? echo $_GET['hash']; ?>" name="hash" />
				<fieldset>
					<label id="password1">Password</label>
					<input type="password" name="password1" id="password1" />
					<label id="password2">Re-enter Password</label>
					<input type="password" name="password2" id="password2" />
				</fieldset>	
				<input type="submit" value="Change" id="submit" />
			</form>
		</div>
		<div id="footer">
			<? echo $footer; ?>
		</div>
		</div>
	</body>
</html>
