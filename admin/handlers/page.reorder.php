<?
include("../salus.php");

function setPageOrder($pageId, $order)
{
	//update database
	$page = new Page();
	$page->Get($pageId);
	$page->sortOrder = $order;
	$page->Save();
}

function doPageOrder($pageOrderStr)
{
	$pagesInOrder = split("&", $pageOrderStr);
	$pageOrder = 1;
	foreach($pagesInOrder as $pageId)
		setPageOrder(substr($pageId, 17) ,$pageOrder++);
}

if($_SESSION["loggedIn"] == true)
	doPageOrder($_POST['orderHash']);
else
	echo "Authentication Error!";

?>
