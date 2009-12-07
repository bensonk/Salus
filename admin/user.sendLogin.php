<?
include("salus.php");
$user = new User();
$user -> Get($_SESSION['userId']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title><? echo $shortTitle; ?> Administration - Send Login Details</title>
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
        
        $('#sendLogin').ajaxForm(options);
           return false; 
		});
		
		function fadeSaveIn(){
			$("#output").html('<img src="imgs/loading_small.gif" alt="loading" /> Finding account...');
			$("#output").fadeIn(500);
		}
		</script>
	</head>
	<body>
		<div id="container">
		<div id="header">
			<div id="title">
				<h1><a href="index.php"><? echo $shortTitle; ?> Administration</a> &raquo; Send Login Details</h1>
			</div>
			<div id="account"></div>
			<div id="headerBottom"></div>
		</div>	
		<div id="oneColumn">
			<p>To have you login information e-mailed to you, please enter your <strong>e-mail address</strong>
			in the field below. If	you need a new account, please contact the site administrator.</p><br />
			<div id="output"></div>	
			<form id="sendLogin" method="post" action="handlers/user.sendLogin.php">		
				<fieldset>
					<label id="emailAddress">E-mail Address</label>
					<input type="text" name="emailAddress" id="emailAddress" />
				</fieldset>
				<input type="submit" value="Send" id="submit" />
			</form>
		</div>
		<div id="footer">
			<? echo $footer; ?>
		</div>
		</div>
	</body>
</html>
