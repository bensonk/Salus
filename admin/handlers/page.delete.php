<?
include("../salus.php");

if($_SESSION['loggedIn'] == true && $_SESSION['userLevel'] < 3)
{
	$page = new Page();
	$page->Get($_POST["pageId"]);
	$page->Delete();
}
else
{
	echo "Authentication error!";
}
?>
