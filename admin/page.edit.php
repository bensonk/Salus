<?
include("salus.php");

function safeContent($code)
{
	$blocks = preg_split("/(<\?(php)?)|(\?>)/m", $code);
	$ret = "";
	$isCodeBlock = false;
	foreach($blocks as $block)
	{
		if($isCodeBlock)
		{
			$block = preg_replace("/>/",  "&gt;",    $block);
			$block = preg_replace("/</",  "&lt;",    $block);
			$block = preg_replace("/\n/", "<br/>\n", $block);
			$ret .= "{{{";
		}
		$ret .= $block;
		if($isCodeBlock)
			$ret .= "}}}";
		// We split on code block delimiters, so every other block is a code block
		$isCodeBlock = !$isCodeBlock;
	}
	return $ret;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title><? echo $shortTitle; ?> Administration - Editing <? echo $page->shortTitle; ?></title>
		<link href="css/screen.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />
		<script language="javascript" type="text/javascript" src="js/jquery-compressed.js"></script>
		<script language="javascript" type="text/javascript" src="js/salus.js"></script>				
		<script language="javascript" type="text/javascript" src="js/jquery.thickbox.js"></script>
		<script language="javascript" type="text/javascript" src="js/jquery.form.js"></script>
		<?
		if(checkTag("_code"))
		{
			/* If the page has the "_code" tag, we don't display the wysiwyg editor, we just *\
			\* give them raw code.  This is the beginning of that version of the page.       */
			$content = trim($page->content);
		}
		else
		{
			/* This is the end of the block of code designed to handle the behavior for the "_code" *\ 
			\* tag.  At this point, we display the edit page, with the editor and everything.       */
		?>
		<script language="javascript" type="text/javascript">
			_editor_url  = "<? echo $adminUrl; ?>/js/xinha/";
			_editor_lang = "en";
			_editor_skin = "blue-look";
		</script>
		<script language="javascript" type="text/javascript" src="js/xinha/XinhaCore.js"></script>
		<script language="javascript" type="text/javascript" src="js/xinha/config.js"></script>
		<? 
			// This calls the safeContent function to turn any php code into html elements wysiwyg editors can handle:
			$content = trim(safeContent($page->content));
		}
		?>
		<script language="javascript" type="text/javascript">
		$(function() {
        	var options = {target: '#editOutput', before: fadeSaveIn, after: fadeSaveOut};
			$('#edit').ajaxForm(options);
			return false; 
			});
			
			
		function fadeSaveOut(){
			$("#editOutput").fadeTo(5000, .01);
		}
		
		function fadeSaveIn(){
			$("#editOutput").html('<img src="imgs/loading_small.gif" alt="loading" /> Saving page...');
			$("#editOutput").fadeTo(200, 1);
		}
		
		function help(file){
			$("div#help").load("help/page.php?topic=" + file);
			}
		</script>
	</head>
	<body>
		<div id="container">
		<? 
		if($_SESSION['loggedIn'] == true)
		{
			/* This is the conditional that makes sure the user is logged in.  What follows is the page that will be displayed if they are logged in.  */
		?>
		<div id="header">
			<div id="title">
				<h1><a href="index.php"><? echo $shortTitle; ?> Administration</a> &raquo; Editing <? echo $page->shortTitle; ?></h1>
			</div>
			<div id="account">
				<? echo $accountPanel; ?>
			</div>
			<div id="headerBottom"></div>
		</div>
		<div id="leftColumn">
			<div id="output"></div>	
			<form id="edit" name="edit" method="post" action="handlers/page.save.php" autocomplete="off">
				<fieldset>
					<input type="hidden" name="pageId" value="<? echo $page->pageId; ?>" />
					<label id="shortTitle">Menu Title</label>
					<input type="text" name="shortTitle" id="shortTitle" value="<? echo $page->shortTitle; ?>" onfocus="help('shortTitle');" />
				</fieldset>
				<fieldset>
					<label id="longTitle">Page Title</label>
					<input type="text" name="longTitle" id="longTitle" value="<? echo $page->longTitle; ?>" onfocus="help('longTitle');" />
				</fieldset>
				<fieldset>
					<label id="tags">Tags</label>
					<input type="text" name="tags" id="tags" value="<? echo $page->tags; ?>" onfocus="help('tags');" />
				</fieldset>
				<fieldset>
					<label id="content">Content</label>
					<textarea id="content" name="content" onfocus="help('content');"><? echo $content; ?></textarea>
				</fieldset>
				<div id="editOutput"></div>
				<div id="editSubmit"><input type="submit" value="Save" id="submit" /></div>
				<br class="clearBoth" />
			</form>	
		</div>
		<div id="rightColumn">
			<h2>Page Tasks</h2>
			<ul>
				<li id="preview">
					<a href="../index.php?id=<? echo $page->pageId; ?>" target="_blank">Preview</a>
				</li>
				<li id="help">
					<a href="http://help.findsalus.com" target="_blank">Help</a><br />
					<div id="help">
						<p>Select a field for quick help.</p>
					</div>
				</li>
			</ul>
		</div>
		<?
		/* This is the end of the conditional that makes sure the user is logged in.  What follows is the page that will be displayed if no one is logged in.  */
		}
		else
			include("dialogs/user.login.php");
		?>
		<div id="footer">
			<? echo $footer; ?>
		</div>
		</div>
</html>
