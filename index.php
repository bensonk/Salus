<?  include("admin/salus.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<? getMetaTags(); ?>
		<title><? echo $longTitle . " - " . $page->longTitle; ?></title>
		<link href="css/screen.css" rel="stylesheet" type="text/css" media="all" />
	</head>
	<body>
		<div id="container">
			<div id="header">
				<h1><? echo $page->longTitle; ?></h1>
			</div>
			<div id="leftColumn">
					<? echo evalContent($page->content); ?>
			</div>
			<div id="rightColumn">
			<? 
				print getListMenu("NOT _hidden", "teh_id", "teh_class"); 
				//print getSeparatedMenu("NOT _hidden", " / ", "teh_id", "teh_class");
				//print getEncapsulatedMenu("NOT _hidden", "[ ", " ]<br/>\n", "teh_id", "teh_class");
			?>
			</div>	
			<div id="footer">
				<hr />
				<a href="http://www.findsalus.com/" target="_blank">
					<img src="admin/imgs/logo.png" alt="Powered by Salus" />
				</a>
				<br />
				<? showEdit(); ?>
			</div>
		</div>
	</body>
</html>
