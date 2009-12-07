<? include("salus.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title><? echo $shortTitle; ?> Administration - Home</title>
		<link href="css/screen.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />
		<script language="javascript" type="text/javascript" src="js/jquery-compressed.js"></script>
		<script language="javascript" type="text/javascript" src="js/salus.js"></script>				
		<script language="javascript" type="text/javascript" src="js/jquery.thickbox.js"></script>
		<script language="javascript" type="text/javascript" src="js/jquery.form.js"></script>
		<script language="javascript" type="text/javascript" src="js/interface.js"></script>
		<script language="javascript" type="text/javascript">
			function deleteConfirm(pageId){
				TB_remove();
				$.ajax({ type: "POST", url: "handlers/page.delete.php", data: "pageId=" + pageId, success: function(){
					$("#pageId" + pageId).Puff(1500);
					$("#message").html('<img src="imgs/icons/page_delete.gif" alt="Page deleted." /> Page deleted.');
					$("#message").fadeTo(5000, .7);
						}
					});
				}
			
			function showHash(){
				serial = $.SortSerialize('pageList');
				$.post("handlers/page.reorder.php", { orderHash: serial.hash });
				}
			
			$(document).ready(function() {
				$('ul#pageList').Sortable(
					{
						accept : 'sortableitem',
						containment : 'ul#pageList',
						opacity : 	0.5,
						onStop : showHash,
						fit :	true
					}
				)
			});
		</script>
	</head>
	<body>
		<div id="container">
		<? 
		/* --------- Check login, and begin to display acutal page -------- */
		if($_SESSION['loggedIn'] == true)
		{ 
		?>
		<div id="header">
			<div id="title">
				<h1><a href="index.php"><? echo $shortTitle; ?> Administration</a> &raquo; Home</h1>
			</div>
			<div id="account">
				<? echo $accountPanel; ?>
			</div>
			<div id="headerBottom"></div>
		</div>	
		<div id="leftColumn">
			<h2>Pages</h2>
			<div id="message"></div>
			<div id="foo"></div>
			<ul id="pageList">	
				<?
				$page = new Page();
				$pagesList = $page->GetList(array(array("pageId", ">", 0)), "sortOrder");
				foreach ($pagesList as $page)
				{
					$level = "";

					$i = 0;
					while ($i <= 10) 
					{
						if(checkTag("_level" . $i, $page->pageId))
							$level = "level" . $i;
						$i++;
					}

					if($level == "")
						$level = "level0";

					echo '<li id="pageId'.$page->pageId.'" class="sortableitem">' . "\n";
					echo '<div class="page ' . $level . '">' . $page->shortTitle . '</div>' . "\n";
					echo '<div class="options"><span class="view"><a href="../index.php?id=' . $page->pageId . '" target="_blank">View</a></span>' . "\n";
					echo '<span class="edit"><a href="page.edit.php?id='.$page->pageId.'">Edit</a></span>' . "\n";
					if($_SESSION["userLevel"] < 3)
						echo '<span class="deleteOption"><a href="dialogs/page.delete.php?&height=85&width=250&pageId=' . $page->pageId . 
							'" title="Delete page ' . $page->shortTitle.'" class="thickbox">Delete</a></span></div>' . "\n";
					echo '</li>' . "\n";
				}
				?>
			</ul>
		</div>
		<div id="rightColumn">
			<h2>Other Tasks</h2>
			<ul>
				<? 
				if($_SESSION["userLevel"] < 3)
					echo '<li id="addNewPage"><a href="dialogs/page.add.php?&height=50&width=300&" class="thickbox" title="Add New Page">Add New Page</a></li>';
				?>
				<li id="users"><a href="user.list.php">Users</a></li>
				<? if($_SESSION["userLevel"] < 2){echo '<li id="addNewUser"><a href="user.add.php">Add User</a></li>';} ?>
				<? if($_SESSION["userLevel"] < 2){echo '<li id="settings"><a href="settings.php">Settings</a></li>';} ?>
				<li id="help"><a href="http://help.findsalus.com" target="_blank">Help</a></li>
			</ul>
		</div>
		<?
		}
		/* -------- End of actual page.  This is what gets displayed if there isn't a current login: --------- */
		else
			include("dialogs/user.login.php");
		?>
		<div id="footer">
		<? echo $footer; ?>
		</div>
		</div>
	</body>
</html>
