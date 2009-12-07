<?
include("../salus.php");

//check for authentication
if($_SESSION["loggedIn"] == true && $_SESSION["userLevel"] < 2)
{
	//create up user object
	$message = "";
	
	//check username
	if(strlen($_POST["username"]) >= 2)
	{
		$username = new User();
		$userList = $username->GetList(array(array("username", "=", $_POST['username'])));
		
		if(sizeof($userList) > 0)
			$message = "Username already exists. Please select a unique username.";
		else
			$user = new User();
	}
	else
	{
		$message = "Please enter a username.";
	}

	//validate firstName
	if(strlen($_POST["firstName"]) < 2)
		if($message == "")
			$message = "Please enter a First Name.";

	//validate  lastName
	if(strlen($_POST["lastName"]) < 2)
		if($message == "")
			$message = "Please enter a Last Name.";
		
	//check email address
	if (preg_match('/^[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)*\@[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)+$/i', $_POST["emailAddress"]))
		$validEmail = true;
	else
		$validEmail = false;

	if($validEmail <> true)
		if($message == "")
			$message = "Please enter a valid e-mail address.";

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
		$user->firstName = $_POST["firstName"];
		$user->lastName = $_POST["lastName"];
		$user->emailAddress = $_POST["emailAddress"];
		$user->password = $pass;
		$user->username = $_POST["username"];
		$user->userLevel = $_POST["userLevel"];
	}	

	//save record
	if($message == "")
	{
		if ($user->Save())
			echo '<img src="imgs/icons/user.gif" alt="Saved" />&nbsp;&nbsp;User account added.<script>$("#accountForm").get(0).reset();</script>';
		else
			echo '<img src="imgs/icons/error.gif" alt="Error" />&nbsp;&nbsp;There was an error saving your account';
	}
	else
	{
		echo '<img src="imgs/icons/error.gif" alt="Error" />&nbsp;&nbsp;' . $message;
	}
}
else
{
	echo "Authentication error!";
}
?>
