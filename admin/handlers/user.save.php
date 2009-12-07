<?
include("../salus.php");

//set account to be saved
if(!$_POST['userId'])
	$userId = $_SESSION['userId'];

else
	$userId = $_POST['userId'];	

//check for authentication
if($_SESSION["loggedIn"] == true)
{
	//create up user object
	$user = new User();
	$user->Get($userId);

	$message = "";

	//set fields
	$user->firstName = $_POST["firstName"];
	$user->lastName = $_POST["lastName"];

	//check email address
	if (preg_match('/^[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)*\@[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)+$/i', $_POST["emailAddress"]))
		$validEmail = true;
	else
		$validEmail = false;

	if($validEmail == true)
		$user->emailAddress = $_POST["emailAddress"];
	else
		$message = "Please enter a valid e-mail address.";

	//check user level
	if($_POST['userLevel'] >= $_SESSION['userLevel'])
		$user->userLevel = $_POST['userLevel'];
	else
		$message = "You have insufficient permissions to set that user level!";

	//check password
	if($_POST["password1"] == $_POST["password2"] && $_POST["password1"] <> "")
	{
		if(strlen($_POST["password1"]) >= 6)
		{
			$pass = md5($_POST["password1"]);
			$user->password = $pass;
		}
		else
		{
			$message = "Password must be at least 6 characters.";
		}
	}
	else
	{
		if($_POST["password1"] <> "" || $_POST["password2"] <> "")
			$message = "Passwords must match.";
	}


	//save record
	if ($user->Save())
	{
		if($message == "")
			echo 'Account information saved.&nbsp;&nbsp;<img src="imgs/icons/user.gif" alt="Saved" />';
		else
			echo $message . '&nbsp;&nbsp;<img src="imgs/icons/error.gif" alt="Error" />';
	}	
	else
	{
		echo 'There was an error saving your account&nbsp;&nbsp;<img src="imgs/icons/error.gif" alt="Error" />';
	} 
}
?>
