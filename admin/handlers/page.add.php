<?
include("../salus.php");

if($_SESSION["loggedIn"] == true && $_SESSION["userLevel"] < 3)
{
	//set up page object
	$page = new Page();

	//set fields
	$page->shortTitle = $_POST["shortTitle"];
	$page->longTitle = $_POST["shortTitle"];
	$page->tags = "_level0";
	$page->content = "<p>Coming soon!</p>";

	$user = new User();
	$user->Get($_SESSION["userId"]);

	$page->authorCreated = $user->username;
	$page->dateCreated = date("Y-m-d");

	//save record
	if ($page->SaveNew())
	{
//set new display row for page
echo <<< END
<script language="text/javascript">
var pageRow = '<li id="pageId$page->pageId" class="pageNew sortableitem"><div class="page level0">$page->shortTitle</div><div class="options"><span class="view"><a href="../index.php?id=$page->pageId">View</a></span><span class="edit"><a href="page.edit.php?id=$page->pageId">Edit</a></span><span class="deleteOption"><a href="dialogs/page.delete.php?&height=85&width=250&pageId=$page->pageId" title="Delete page $page->shortTitle" class="thickbox" id="delete$page->pageId">Delete</a></span></div></li>';
</script>
END;

echo <<< END
<img src="imgs/icons/page_add.gif" alt="Page added" /> Page added successfully. (<a href="page.edit.php?id=$page->pageId">edit</a>)
<script language="text/javascript">
$("#pageList").append(pageRow);
TB_register('delete$page->pageId');
$("#pageList").SortableAddItem(document.getElementById('pageId$page->pageId'));
showHash();
</script>
END;
	}
	else
	{
		echo '<img src="imgs/icons/warning.gif" alt="Error" /> There was an error adding the page.';
	} 
}
else
{
	echo "Authentication failure!";
}
?>
