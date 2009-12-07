<?
include("../salus.php");

function cleanContent($code)
{
	// Strip escapes added by magic_quotes_gpc php.ini directive
	if(get_magic_quotes_gpc())
		$code = stripcslashes($code);
	$blocks = preg_split("/({{{)|(}}})/", $code);
	$ret = "";
	$isCodeBlock = false;
	foreach($blocks as $block)
	{
		if($isCodeBlock)
		{
			$block = preg_replace("/&gt;/", ">",  $block);
			$block = preg_replace("/&lt;/", "<",  $block);
			$block = preg_replace("/&nbsp;/", " ", $block);
			$block = preg_replace("/(<br\s*\/>)|(<p>)|(<\/p>)/", "", $block);
			$ret .= "<?";
		}
		$ret .= $block;
		if($isCodeBlock)
			$ret .= "?>";
		$isCodeBlock = !$isCodeBlock;
	}

	return $ret;
}

if($_SESSION["loggedIn"] == true)
{
	//set up page object
	$page = new Page();
	$page->Get($_POST["pageId"]);

	//set fields
	$page->shortTitle = $_POST["shortTitle"];
	$page->longTitle = $_POST["longTitle"];
	$page->tags = $_POST["tags"];
	$page->content = cleanContent($_POST["content"]);

	$user = new User();
	$user->Get($_SESSION["userId"]);

	$page->authorModified = $user->username;
	$page->dateModified = date("Y-m-d");

	//save record
	if ($page->Save())
		echo '<img src="imgs/icons/disk.gif" alt="Saved" /> Page saved.';
	else
		echo '<img src="imgs/icons/error.gif" alt="Error" /> There was an error saving the page';
}
else
{
echo <<<END
<script>
TB_show("Authentication Error", "dialogs/user.authError.php?&height=250&width=300&");
</script>
END;
}
?>
