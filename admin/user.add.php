<?
include("salus.php");
$user = new User();
$user -> Get($_SESSION['userId']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title><? echo $shortTitle; ?> Administration - Add User</title>
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
			$("#output").fadeTo(5000, .6);
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
				<h1><a href="index.php"><? echo $shortTitle; ?> Administration</a> &raquo; Add User</h1>
			</div>
			<div id="account">
				<? echo $accountPanel; ?>
			</div>
			<div id="headerBottom"></div>
		</div>	
		<div id="leftColumn">
			<br />
			<div id="output"></div>	
			<form id="accountForm" method="post" action="handlers/user.add.php" autocomplete="off">		
				<fieldset>
					<label id="username">Username</label>
					<input type="text" name="username" id="username" />
				</fieldset>				
				<fieldset>
					<label id="userLevel">User Permissions</label>
					<select id="userLevel" name="userLevel">
						<? if($_SESSION['userLevel'] == 0){echo '<option value=0>0 - Administrator</option>';} ?>
						<option value=1>1 - Super-user</option>
						<option selected value=2>2 - User</option>
						<option value=3>3 - Restricted user</option>
					</select>
				</fieldset>
				<fieldset>
					<label id="firstName">First Name</label>
					<input type="text" name="firstName" id="firstName" />
				</fieldset>
				<fieldset>
					<label id="lastName">Last Name</label>
					<input type="text" name="lastName" id="lastName" />
				</fieldset>
				<fieldset>
					<label id="emailAddress">E-mail Address</label>
					<input type="text" name="emailAddress" id="emailAddress" />
				</fieldset>
				<fieldset>
					<label id="password1">Password</label>
					<input type="password" name="password1" id="password1" />
					<label id="password2">Re-enter Password</label>
					<input type="password" name="password2" id="password2" />
				</fieldset>						
				<input type="submit" value="Add User" id="submit" />
			</form>
		</div>
		<div id="rightColumn">
		<h2>Related Tasks</h2>
			<ul>
				<li id="users"><a href="user.list.php">List Users</a></li>
				<li id="myAccount"><a href="user.account.php">My Account</a></li>
			</ul>
		<h3>Add User</h3>
		
		<p>Each user must have a unique username.</p>
		<h4>User Levels</h4>
		<ul id="addUser">
			<li><strong>Super-user</strong> - Can perform all tasks, including changing settings and adding and deleting other users. Cannot delete other Super-users or Admins.</li>
			<li><strong>User</strong> - Can add, delete, and edit pages, but not manage other users.</li>
			<li><strong>Restricted User</strong> - Can edit pages only.</li>
		</ul>
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
