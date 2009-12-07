<?
include("../salus.php");
$user = new User();
$user->Get($_POST["userId"]);
if($_SESSION['loggedIn'] == true && $_SESSION['userLevel'] < $user->userLevel)
	$user->Delete();
else
	echo "Authentication error!";
?>
