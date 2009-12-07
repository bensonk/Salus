<?
include("../salus.php");
$message = "";
$user = new User();
$user->Get($_POST['userId']);
$newHash = md5($user->password . date("Ymd"));

if($_POST['hash'] == $newHash)
{
	//check password
	if($_POST["password1"] <> $_POST["password2"])
		if($message == "")
			$message = "Passwords must match.";

	if(strlen($_POST["password1"]) < 6)
		if($message == "")
			$message = "Password must be at least 6 characters.";

	//if no errors, set fields and save
	if($message == "")
	{
		$pass = md5($_POST["password1"]);
		$user->password = $pass;
	}	

	//save record
	if($message == "")
	{
		if ($user->Save())
			echo '<img src="imgs/icons/lock.gif" alt="Saved" />&nbsp;Password changed. <a href="index.php">Continue to login page...</a>';
		else
			echo '<img src="imgs/icons/error.gif" alt="Error" />&nbsp;There was an error saving your account.';
	}		
	else
	{
		echo '<img src="imgs/icons/error.gif" alt="Error" />&nbsp;&nbsp;' . $message;
	}
}
else
{
	echo '<img src="imgs/icons/error.gif" alt="Error" /> Failed to authenticate.';
}
?>
