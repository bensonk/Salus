<?
include("salus.php");

function userLevel($lvl){
	if($lvl == 0)
		return "Administrator";
	if($lvl == 1)
		return "Super-user";
	if($lvl == 2)
		return "User";
	if($lvl == 3)
		return "Restricted User";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title><? echo $shortTitle; ?> Administration - Users</title>
		<link href="css/screen.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />
		<script language="javascript" type="text/javascript" src="js/jquery-compressed.js"></script>
		<script language="javascript" type="text/javascript" src="js/salus.js"></script>						
		<script language="javascript" type="text/javascript" src="js/jquery.thickbox.js"></script>
		<script language="javascript" type="text/javascript">	
			function deleteConfirm(userId){
				TB_remove();
				$.ajax({ type: "POST", url: "handlers/user.delete.php", data: "userId=" + userId, success: function(){
					$("#userId" + userId).fadeOut(5000);
					$("#message").html('<img src="imgs/icons/user_delete.gif" alt="User deleted." /> User deleted.');
					$("#message").fadeTo(5000, .7);
						}
					});
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
				<h1><a href="index.php"><? echo $shortTitle; ?> Administration</a> &raquo; Users</h1>
			</div>
			<div id="account">
				<? echo $accountPanel; ?>
			</div>
			<div id="headerBottom"></div>
		</div>	
		<div id="leftColumn">
			<h2>Users</h2>
			<div id="message"></div>
			<ul>	
				<?
				$user = new User();
					$userList = $user->GetList(array(array("userId", ">", 0)));
					foreach ($userList as $user)
							{
								//clear out values and set defaults
								$delete = '';	
								$me = '';
								$email = '';
								$userLevel = userLevel($user->userLevel);
								
	    						echo '			<li id="userId'.$user->userId.'">' . "\n";		
				
								//check for self
								if($user->userId == $_SESSION['userId'])
									$me = ' <span class="me">Me (<a href="user.account.php">Edit Account</a>)</span>';		
								else
									$email = '<span class="email"><a href="mailto:' . $user->emailAddress . '">Email</a></span>';
				
				
								//check if user is editable
								if($user->userLevel > $_SESSION['userLevel'] && $_SESSION['userLevel'] < 2){
									echo '				<div class="user userEdit"><a href="user.account.php?user=' . $user->userId . '">' . $user->firstName . ' ' .$user->lastName . ' (' . $user->username . ')</a></div>' . "\n";
									$delete = '<span class="deleteOption"><a href="dialogs/user.delete.php?&height=85&width=250&userId=' . $user->userId . '" title="Delete user '.$user->username.'?"class="thickbox">Delete</a></span>';
									}
									else
										echo '				<div class="user">' . $user->firstName . ' ' . $user->lastName . "</div>\n";
									
								echo '				<div class="userSmall">' .  "\n";
								echo '				' . $me . '<span class="userLevel">'. $userLevel .'</span>' . $email . $delete .'</div>' . "\n";
	    						echo '			</li>' . "\n";
							}
				?>
			</ul>
		</div>
		<div id="rightColumn">
			<h2>Other Tasks</h2>
			<ul>
				<? if($_SESSION["userLevel"] < 2){echo '<li id="addNewUser"><a href="user.add.php">Add User</a></li>';} ?>
				<li id="myAccount"><a href="user.account.php">My Account</a></li>
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
