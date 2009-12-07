<?
	include("../salus.php");
	$user = new User();
	$userList = $user->GetList(array(array("username", "=", $_POST['username'])));
    $user = $userList [0];
	$pass = md5($_POST['password']);
	
	if ($user->password == $pass)
	{
	    $_SESSION['userId'] = $user->userId;
	    $_SESSION['loggedIn'] = true;
		$_SESSION['userLevel'] = $user->userLevel;
	    $message = 'You are now logged in.<script language="text/javascript">TB_remove();  $("#edit").trigger("submit"); </script>';
	}
		
	else
	{
		$message =  '<img src="imgs/icons/error.gif" alt="Error" /> Invalid username or password.';
	}
	
	echo $message;
?>