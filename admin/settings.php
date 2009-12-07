<? include("salus.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title><? echo $shortTitle; ?> Administration - Site Settings</title>
		<link href="css/screen.css" rel="stylesheet" type="text/css" media="screen" />
		<script language="javascript" type="text/javascript" src="js/jquery-compressed.js"></script>
		<script language="javascript" type="text/javascript" src="js/salus.js"></script>				
		<script language="javascript" type="text/javascript" src="js/jquery.form.js"></script>		
		<script language="javascript" type="text/javascript">
		$(function() {	
			
        var options = {
			target:   '#output',
			before: fadeSaveIn,
			after: fadeSaveOut
        };
        
        $('#settingsForm').ajaxForm(options);
           return false; 
		});
		
		function fadeSaveOut(){
			$("#output").fadeTo(3000, .8);
		}
		
		function fadeSaveIn(){
			$("#output").html('<img src="imgs/loading_small.gif" alt="loading" /> Saving settings...');
			$("#output").fadeIn(500);
		}
		
		function help(topic){
			$("div#help").load("help/settings.php?topic=" + topic, 
				function(){
						$("#help").insertAfter("input#" +topic);
				});
		}
		</script>
	</head>
	<body>
		<div id="container">
		<? 
		if($_SESSION['loggedIn'] == true)
		{ 
			/* ---------- Beginning of data that only gets output if the user is currently logged in ---------- */
		?>
		<div id="header">
			<div id="title">
				<h1><a href="index.php"><? echo $shortTitle; ?> Administration</a> &raquo; Site Settings</h1>
			</div>
			<div id="account">
				<? echo $accountPanel; ?>
			</div>
			<div id="headerBottom"></div>
		</div>	
		<div id="leftColumn">		
			<form id="settingsForm" method="post" action="handlers/settings.save.php" autocomplete="off">
			<div id="output"></div>		
				<br />
				<fieldset>
					<label id="longTitle">Full Site Title</label>
					<input type="text" name="longTitle" id="longTitle" value="<? echo $longTitle; ?>" onfocus="help('longTitle');" />
				</fieldset>
				<fieldset>
					<label id="shortTitle">Short Site Title</label>
					<input type="text" name="shortTitle" id="shortTitle" value="<? echo $shortTitle; ?>" onfocus="help('shortTitle');"  />
				</fieldset>
				<fieldset>
					<label id="metaTags">Site-wide Metatags</label>
					<input type="text" name="metaTags" id="metaTags" value="<? echo $metaTags; ?>" onfocus="help('metaTags');"  />
				</fieldset>
				<fieldset>
					<label id="adminEmail">Administrator's E-mail</label>
					<input type="text" name="adminEmail" id="adminEmail" value="<? echo $adminEmail; ?>" onfocus="help('adminEmail');"  />
				</fieldset>
				<fieldset>
					<label id="siteUrl">Site URL</label>
					<input type="text" name="siteUrl" id="siteUrl" value="<? echo $siteUrl; ?>" onfocus="help('siteUrl');"  />
				</fieldset>
				<fieldset>
					<label id="adminUrl">Administration URL</label>
					<input type="text" name="adminUrl" id="adminUrl" value="<? echo $adminUrl; ?>" onfocus="help('adminUrl');"  />
				</fieldset>
				<input type="submit" value="Save Changes" id="submit" />
			</form>
		</div>
		<div id="rightColumn">
		<h3>Site Settings</h3>
		
		<p>These settings control both the configuration of your website and the function of the administrative functions. For help on any setting, select the field and click the question mark below it to go to the manual entry for that setting.
		</p>
		<div id="help"></div>
		</div>
		<?
			/* ---------- End of logged-in page ---------- */
		}
		else // If the user isn't logged in, display login dialog:
			include("dialogs/user.login.php");
		?>
		<div id="footer">
			<? echo $footer; ?>
		</div>
		</div>
	</body>
</html>
