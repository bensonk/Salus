<?
if($_SESSION['loggedIn'] <> true)
{
	$user = new User();
	$userList = $user->GetList(array(array("username", "=", $_POST['username']))); 
	$user = $userList [0]; 
	$pass = md5($_POST['password']); 
	if ($user->password == $pass)
	{
		$_SESSION['userId'] = $user->userId;
		$_SESSION['loggedIn'] = true;
		$_SESSION['userLevel'] = $user->userLevel;
		$message = "You are now logged in.";
	}
	else
	{
		if($_POST['username'] <> "")
			$message =  '<img src="imgs/icons/error.gif" alt="Error" /> Invalid username or password.';
		else
			$message = '<img src="imgs/icons/lock_go.gif" alt="Login" /> Please login to continue.';
	}
}
if($_GET['logout'] == true)
{
	$_SESSION['loggedIn'] = false;
	$message = '<img src="imgs/icons/lock_break.gif" alt="Logged out" /> You are now logged out.';
}
?>
