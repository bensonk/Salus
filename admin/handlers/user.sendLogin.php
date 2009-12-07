<?
include("../salus.php");

$message = "";
$user = new User();
$userList = $user->GetList(array(array("emailAddress", "=", $_POST['emailAddress'])));

//check for email address
if(sizeof($userList) != 0)
{
	$user = $userList[0];
	$message = 'Your account details have been sent to <strong>' . $_POST['emailAddress'] . '</strong>. <img src="imgs/icons/accept.gif" alt="Account details sent" />';

	//set email
	$hash = md5($user->password . date("Ymd"));
	$tz = date("T");
	$to  = $user->emailAddress;
	$subject = $longTitle . ' Account details for ' . $user->firstName . ' ' . $user->lastName;
	$body = <<<END
$user->firstName,

Your login information for $longTitle is below. If you did not request this information, please disregard this e-mail.

Username: $user->username

If you have forgotten your password, please follow this link to reset it: $adminUrl/user.passwordReset.php?userId=$user->userId&hash=$hash

Please note the above access is valid until midnight ($tz) for security reasons. If you do not reset your password before midnight but still wish to do so, you can simply re-request this e-mail at $adminUrl/user.sendLogin.php.

-------------------
$adminUrl/
END;

	$sender = $longTitle . ' Administration <' . $adminEmail . '>';
	mail($to, $subject, $body, "From: $sender");
}
else
{
	$message = 'E-mail address not found! <img src="imgs/icons/error.gif" alt="Error" />';
}

echo $message;
?>
