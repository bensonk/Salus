<?
include("salus.php");

//if editing own account
if(!$_GET['user'])
{
	$userId = $_SESSION['userId'];	
	$pageTitle = "My Account";
}


//if editing other user
else
{
	$userId = $_GET['user'];	
	$pageTitle = "Editing User";
}

$user = new User();
$user->Get($userId);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title><? echo $shortTitle; ?> Administration - <? echo $pageTitle; ?></title>
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
        
        $('#accountForm').ajaxForm(options);
           return false; 
		});
		
		function fadeSaveOut(){
			$("#output").fadeTo(3000, .8);
		}
		
		function fadeSaveIn(){
			$("#output").html('<img src="imgs/loading_small.gif" alt="loading" /> Saving account...');
			$("#output").fadeIn(500);
		}
		</script>
	</head>
	<body>
		<div id="container">
		<? 
		if($_SESSION['loggedIn'] == true)
		{
			/* ---------- Beginning of page displayed to logged in users ---------- */
		?>
		<div id="header">
			<div id="title">
				<h1><a href="index.php"><? echo $shortTitle; ?> Administration</a> &raquo; <? echo $pageTitle; ?></h1>
			</div>
			<div id="account">
				<? echo $accountPanel; ?>
			</div>
			<div id="headerBottom"></div>
		</div>	
		<div id="leftColumn">
			<form id="accountForm" method="post" action="handlers/user.save.php">
			<div id="output"></div>			
			<input name="userId" type="hidden" value="<? echo $_GET['user']; ?>" />				
			<fieldset>
					<label id="username">Username</label>
					<input type="text" name="username" id="username" value="<? echo $user->username; ?>" disabled />
				</fieldset>				
				<? if($_GET['user'])
					{
					if($user->userLevel == 0)
						$level[0] = " selected";					
					if($user->userLevel == 1)
						$level[1] = " selected";
					if($user->userLevel == 2)
						$level[2] = " selected";
					if($user->userLevel == 3)
						$level[3] = " selected";					
				?>				
				<fieldset>
					<label id="userLevel">User Permissions</label>
					<select id="userLevel" name="userLevel">
						<?
							if($_SESSION['userLevel'] == 0)
								echo '<option value=0' . $level[0] . '>0 - Administrator</option>';
						
							if($_SESSION['userLevel'] <= 1)
								echo '<option value=1' . $level[1] . '>1 - Super-user</option>';

						   if($_SESSION['userLevel'] <= 2)
								echo '<option value=2' . $level[2] . '>2 - User</option>';						
							echo '<option value=3'. $level[3] . '>3 - Restricted user</option>';
						?>
					</select>
				</fieldset>
				<? } ?>
				<fieldset>
					<label id="firstName">First Name</label>
					<input type="text" name="firstName" id="firstName" value="<? echo $user->firstName; ?>" />
				</fieldset>
				<fieldset>
					<label id="lastName">Last Name</label>
					<input type="text" name="lastName" id="lastName" value="<? echo $user->lastName; ?>" />
				</fieldset>
				<fieldset>
					<label id="emailAddress">E-mail Address</label>
					<input type="text" name="emailAddress" id="emailAddress" value="<? echo $user->emailAddress; ?>" />
				</fieldset>
				<fieldset>
					<label id="password1">Password</label>
					<input type="password" name="password1" id="password1" value="" />
					<label id="password2">Re-enter Password</label>
					<input type="password" name="password2" id="password2" value="" />
				</fieldset>						
				<input type="submit" value="Save Changes" id="submit" />
			</form>
		</div>
		<div id="rightColumn">
			<h2>Related Tasks</h2>
			<ul>
				<li id="users"><a href="user.list.php">List Users</a></li>
				<? if($_SESSION["userLevel"] < 2){echo '<li id="addNewUser"><a href="user.add.php">Add User</a></li>';} ?>
				<? if($_GET['user']){echo '<li id="myAccount"><a href="user.account.php">My Account</a></li>';} ?>
			</ul>
		<h3><? echo $pageTitle; ?></h3>
		
		<p>To update the account password, enter the new password in the two boxes provided. If you're not updating the password, you can
		leave the password boxes blank.</p>	
		</div>
		<?
			/* ---------- End of page displayed to logged in users ---------- */
		}
		else
			include("dialogs/user.login.php");
		?>
		<div id="footer">
			<? echo $footer; ?>
		</div>
		</div>
	</body>
</html>
