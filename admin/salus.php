<?
/* 	Salus v0.2
 *	
 *	Development release
 *	
 *	http://www.findsalus.com
 *
 *	Created by Nathan Carnes
 *	http://www.nathancarnes.com
 *
 *	Development by Nathan Carnes and Benson Kalahar
 */

global $allowableFunctions;
$allowableFunctions = array("displayRss", "listMenu", "separatedMenu", "encapsulatedMenu");
 
/* SET UP SESSION */
session_cache_expire(120);

if (!isset($_SESSION))
	session_start();

// SET PHP TO USE VALID XHTML FORMS FOR SESSION TRACKING 
ini_set('arg_separator.output',';');
ini_set('url_rewriter.tags', 'a=href,area=href,frame=src,input=src,fieldset=');

/* INCLUDES */
include("pog/configuration.php");          // Database settings
include("pog/objects/class.database.php"); // Required database class

include("pog/objects/class.setting.php");  // Settings object from DB
include("handlers/settings.parse.php");    // Parse DB fields into strings

include("pog/objects/class.user.php");     // Necessary for authentication
include("handlers/user.auth.php");         // User authentication
include("pog/objects/class.page.php");     // Page object (Generated by POG)

$page = new Page();
if($_GET['id'] == '')
{
	if($_GET['page'])
	{
		$id = getIdByName($_GET['page']);
	}
	else
	{
		$pagesList = $page->GetList(array(array("pageId", ">", 0)), "sortOrder");
		foreach ($pagesList as $page)
		{
			if(checkTag("_index", $page->pageId))
			{
				$id = $page->pageId;
				break;
			}
		}

		if($id == '')
			$id = $pagesList[0]->pageId;
	}
}
else
{
	$id = $_GET['id'];
}
$page -> Get($id);
$id = $page->pageId;

/* 
 * showEdit()
 * displays a link to edit the current page if the user has appropriate credentials
 */
function showEdit($code = "[Edit this page]")
{
	if($_SESSION['loggedIn'] == true)
	{
		global $adminUrl;
		global $id;
		echo "<a href=\"$adminUrl/page.edit.php?id=$id\" target=\"_blank\">$code</a>";
	}
}

/* 
 * checkTag()
 * evaluates whether a page has a specific tag; returns boolean
 * use: checkTag("foo");
 */
function checkTag($tag, $id = null)
{
	if($id == null)
		global $id;
	$thePage = new Page();
	$thePage -> Get($id);
	$tags = $thePage->tags;
	foreach(preg_split('/,\\s*/', $tags) as $aTag)
		if($aTag == $tag)
			return true;
	return false;
}

/*
 * getMetaTags()
 * Returns: nicely formatted meta keywords from page tags, stripped of system tags
 * ie: <meta name="keywords" content="keywords,go here,separated by a comma,but not a space" />
 */
function getMetaTags()
{
	global $id;
	global $metaTags;
	$page = new Page();
	$page -> Get($id);
	$tags = $page->tags . "," . $metaTags;
	$tagArray = preg_split('/,\\s*/', $tags);
	$tagArray = preg_grep('/^[^\\._]/', $tagArray);
	echo '<meta name="keywords" content="' . join(',', $tagArray). '" />' . "\n";
}

/* 
 * pageHasTags($required_tags, $page)
 * Parameters: A String composed of page tags, and a page object
 * Returns: true if page matches those tags, false otherwise
 */
function pageHasTags($required_tag_str, $page_tag_str)
{
	$page_tags     = preg_split("/\\s*,\\s*/", $page_tag_str);
	$required_tags = preg_split("/\\s+AND\\s+/", $required_tag_str);
	foreach($required_tags as $req_tag)
	{
		$pass = false;
		$not = false;
		if(preg_match("/^NOT\\s+/", $req_tag))
		{
			$not = true;
			$req_tag = preg_replace("/^NOT\\s+/", "", $req_tag);
		}
		foreach($page_tags as $page_tag)
			if($page_tag == $req_tag)
				$pass = true;
		if($pass)  // If it's a NOT and we passed it, then it's a fail
		{
			if($not)
				return false;
		}
		else      // if it's not a NOT, and we failed it, then it fails
		{
			if(!$not)
				return false;
		}
	}
	// If we get here, pass it, since we haven't found any reason not to
	return true;
}

/* 
 * getContent($shortTitle) 
 * Grabs the $content from the specified page
 * Used for quickie content boxes
 * Usage: echo getContent("About");
 */

function getContent($name)
{
	$page = new Page();
	$page -> Get(getIdByName($name));
	echo $page->content;
}

/* 
 * getLevel($the_page)
 * Parameter: A page object
 * Returns: an integer that represents the page's level in the hierarchy.
 * It does a search through tags.  If no matching tag is 
 * found, returns 0, if more than one matching tag is 
 * found, returns the smallest one.
 * Usage: getLevel($some_page);
 */
function getLevel($the_page)
{
	$tags = preg_split('/,\\s*/', $the_page->tags);
	$tags = preg_grep('/^_level\\d+/', $tags);
	sort($tags);
	$level = array_shift($tags);
	if(!$level)
		$level = "_level0";
	return substr($level, 6);
}

/* 
 * linkifyPage($the_page)
 * Parameter: A page object
 * Returns: An html snippet, which is a link to the page passed to it
 * Usage: print linkifyPage($some_page);
 */
function linkifyPage($the_page, $class = null)
{ 
	global $siteUrl;
	return "<a href=\"$siteUrl?page=" . getUrlName($the_page->shortTitle) .
	 	'"' . getHtmlClassStr($class) .'>' . $the_page->shortTitle . "</a>"; 
}

/* 
 * getMatchingPages($tags)
 * Parameter: String describing a set of pages
 * Returns: An array of page objects matching that string
 * Usage: $pages = getMatchingPages("about AND NOT _bio");
 */
function getMatchingPages($tags)
{
	$menu_page = new Page();
	$page_list = $menu_page->GetList(array(array("pageId", ">", 0)), "sortOrder");

	$matching_pages = array();
	foreach($page_list as $menu_page)
		if(pageHasTags($tags, $menu_page->tags))
			array_push($matching_pages, $menu_page);
	return $matching_pages;
}

/* 
 * getIdByName($pageName)
 * Parameter: A page name as passed in by the user
 * (ie: http://example.com/?page=foo $pageName is "foo")
 * Returns: A page id or -1 if it finds nothing
 * usage: $pageId = getIdByName($_GET['page']);
 */
function getIdByName($pageName)
{
	$page_object = new Page();
	$page_list = $page_object->GetList(array(array("pageId", ">", 0)), "sortOrder");
	foreach($page_list as $thePage)
	{
		if(strcasecmp(getUrlName($thePage->shortTitle), getUrlName($pageName)) == 0)
			return $thePage->pageId;
	}
	return -1;
}

/* 
 * getUrlName($page)
 * Parameter: A page short title to return the urlified name of
 * Returns: A version of that name with all spaces replaced by hyphens, and all letters in lower case
 * usage: getUrlName($page->shortTitle)
 */
function getUrlName($name)
{
	$name = strtolower($name);
	$name = preg_replace("/ /", "-", $name);
	return $name;
}

/* 
 * getHtmlIdStr($id) and getHtmlClassStr($class)
 * Parameter: An html id or class element, respectively.  Can be null.
 * Returns: code that can be put into an html tag representing the class or id
 * Usage: print "<ul " . getHtmlClassStr($class_str) . ">";
 */
function getHtmlIdStr($id)
{
	if($id) return " id=\"$id\"";
	else    return "";
}

function getHtmlClassStr($class)
{
	if($class) return " class=\"$class\"";
	else    return "";
}

/* 
 * getListMenu($tags, $id, $class)
 * Parameters:
 * 	$tags -- Tag string describing a set of pages
 * 	$id -- An optional html ID element for the <ul>
 * 	$class -- An optional class element for the <a> and <li> items
 * Returns: Html Snipped representing a menu, in an unordered list
 * Usage: getListMenu(); or getListMenu("about_us AND NOT _hidden", "about_menu", "about");
 */
function getListMenu($tags = "NOT _hidden", $menuId = null, $menuClass = null)
{
	// Set up some initial stuff
	global $id;
	$pages = getMatchingPages($tags);
	$ret = "<ul" . getHtmlIdStr($menuId) . getHtmlClassStr($menuClass) . ">\n";
	for($i = 0; $i < count($pages);)
	{
		if($pages[$i]->pageId == $id)
			$ret = $ret . "<li class=\"current_page\">\n" . linkifyPage($pages[$i]) . "\n";
		else
			$ret = $ret . "<li>\n" . linkifyPage($pages[$i]) . "\n";

		// Find all sub-pages and deal with them:
		$subPages = array();
		for($j = $i+1; $j < count($pages) && getLevel($pages[$i]) < getLevel($pages[$j]); $j++)
			array_push($subPages, $pages[$j]);
		$ret = $ret . getListMenuHelper($subPages);

		$ret = $ret . "</li>\n";
		$i = $j;
	}

	$ret = $ret . "</ul>\n";
	return $ret;
}

/* 
 * getListMenuHelper ($pages)
 * Takes a list of pages and produces nested <ul>s of them
 */
function getListMenuHelper($pages)
{
	// Short circuit out if there's nothing to do
	if(count($pages) == 0)
		return "";
	global $id;
	$ret = "<ul>\n";
	for($i = 0; $i < count($pages);)
	{
		// Add the current page to the list of pages
		if($pages[$i]->pageId == $id)
			$ret = $ret . "<li class=\"current_page\">\n" . linkifyPage($pages[$i]) . "\n";
		else
			$ret = $ret . "<li>\n" . linkifyPage($pages[$i]) . "\n";
		$subPages = array();
		// Find all sub-pages and deal with them recursively:
		for($j = $i+1; $j < count($pages) && getLevel($pages[$i]) < getLevel($pages[$j]); $j++)
			array_push($subPages, $pages[$j]);
		$ret = $ret . getListMenuHelper($subPages);
		// Finish the item and set the counter to the first page not dealt with:
		$ret = $ret . "</li>\n";
		$i = $j;
	}
	return $ret . "</ul>\n";
}

/* 
 * levelize($prevLevel, $level)
 * Helper function for getListMenu()
 */
function levelize($prevLevel, $level)
{
	if($prevLevel == $level)
		return "";
	else if($prevLevel < $level)
		return str_repeat("<ul>\n", $level - $prevLevel);
	else // $prevLevel > $level
		return str_repeat("</ul>\n", $prevLevel - $level);
}

/* 
 * getSeparatedMenu($tags, $separator, $id, $class)
 * Parameters:
 * 	$tags -- Tag string describing a set of pages
 * 	$separator -- Text to be used to separate menu items
 * 	$id -- An optional html ID element for the <ul>
 * 	$class -- An optional class element for the <a> and <li> items
 * Returns: Html Snipped representing a menu, separated by the separator
 * Usage: getSeparatedMenu("about_us AND NOT _hidden", " / ", "about_menu", "about");
 */
function getSeparatedMenu($tags = "NOT _hidden", $separator = " | ", $id = null, $class = null)
{
	$matching_page_links = array();
	foreach(getMatchingPages($tags) as $the_page)
		array_push($matching_page_links, linkifyPage($the_page, $class));
	$ret = "<div" . getHtmlIdStr($id) . ">" . join($separator, $matching_page_links) . "</div>";
	return $ret;
}

/* 
 * getEncapsulatedMenu($tags, $l_encap, $r_encap, $id, $class)
 * Parameters:
 * 	$tags -- Tag string describing a set of pages
 * 	$l_encap -- html to go on the left side of each elmeent
 * 	$r_encap -- html to go on the right side of each element
 * 	$id -- An optional html ID element for the <ul>
 * 	$class -- An optional class element for the <a> and <li> items
 * Returns: Html Snipped representing a menu, in an unordered list
 * Usage: getEncapsulatedMenu("about_us AND NOT _hidden", "<p>", "</p>", "about_menu", "about");
 */
function getEncapsulatedMenu($tags = "NOT _hidden", $l_encap = '[', $r_encap = ']', $id = null, $class = null)
{
	$matching_page_links = array();
	foreach(getMatchingPages($tags) as $the_page)
		array_push($matching_page_links, linkifyPage($the_page, $class));

	$ret = "<div" . getHtmlIdStr($id) . ">";
	foreach ($matching_page_links as $menu_page)
		$ret = $ret . $l_encap . $menu_page . $r_encap;
	$ret = $ret . "</div>";
	return $ret;
}


/*
 * listMenu($tags, $menuId, $menuClass)
 * parameters: see getListMenu()
 * Wrapper function to get a list menu, and then print it.  
 */
function listMenu($tags = "NOT _hidden", $menuId = null, $menuClass = null)
{
	print getListMenu($tags, $menuId, $menuClass);
}

/*
 * separatedMenu($tags, $separator, $id, $class)
 * parameters: see getSeparatedMenu()
 * Wrapper function to get separated list menu, and then print it.  
 */
function separatedMenu($tags = "NOT _hidden", $separator = " | ", $menuId = null, $menuClass = null)
{
	print getSeparatedMenu($tags, $separator, $menuId, $menuClass);
}

/*
 * encapsulatedMenu($tags, $l_encap, $r_encap, $menuId, $menuClass)
 * parameters: see getEncapsulatedMenu()
 * Wrapper function to get an encapsulated menu, and then print it.  
 */
function encapsulatedMenu($tags = "NOT _hidden", $l_encap = '[', $r_encap = ']', $menuId = null, $menuClass = null)
{
	print getEncapsulatedMenu($tags, $l_encap, $r_encap, $menuId, $menuClass);
}

/* 
 * evalContent($code)
 * Parameter: A variable containing mixed html and php code to be evaluated
 * Result: Prints the result of executing that code to the browser
 * Returns: nothing
 */
function evalContent($code)
{
	$blocks = preg_split("/(<\?(php)?)|(\?>)/", $code);
	$isCode = false;
	foreach($blocks as $block)
	{
		if($isCode)
			eval($block);
		else
			restrictedEvalContent($block);
		$isCode = !$isCode;
	}
}

/* 
 * restrictedEvalContent($code)
 * Parameter: A variable containing mixed html and restricted 
 * functions enclosed in <% and %> tags
 * Result: Prints the html, and evaluates the functions if they 
 * match a whitelist called "allowable functions"
 * Returns: nothing
 */
function restrictedEvalContent($code)
{
	global $allowableFunctions;
	$blocks = preg_split("/(<%)|(%>)/", $code);
	$isCode = false;
	foreach($blocks as $block)
	{
		if($isCode)
		{
			preg_match('/\s*([a-zA-Z_][a-zA-Z0-9_]*)\s*\((.*)\);?\s*$/', $block, $parts);
			$funcname = $parts[1];
			$args     = $parts[2];
			foreach($allowableFunctions as $func)
				if($funcname == $func)
					call_user_func_array($funcname, preg_split("/,\s+/", $args));
		}
		else
		{
			print($block);
		}
		$isCode = !$isCode;
	}
}

/*
 * displayRss($feedUrl, $num, $template)
 * Parameters: feedUrl(default is main Newsvine feed for demonstration purposes),
 * num(maximum number of posts to display, default is 8), and template(template 
 * to be used for display; templates should be located in admin/templates, and 
 * named foo.rss.php so that only foo is passed to displayRss)
 * Returns: specified feed formatted for display by the template
 */
function displayRss($feedUrl = "http://www.newsvine.com/_feeds/rss2/index", $num = 8, $template = "default")
{
	include_once("rss.php");
	$feed = new SimplePie();
	//set cache and feed locations
	$feed->feed_url($feedUrl);
	$feed->cache_location('../cache');
	//initialize feed parser and check character encoding
	$feed->init();
	$feed->handle_content_type();
	require("templates/" . $template . ".rss.php");
}

/* ---------- Admin templating: ---------- */

//footer
$footer = <<< END
	<a href="http://www.findsalus.com" target="_blank">
	<img src="imgs/logo.png" alt="Powered by Salus" />
	</a>
END;

//account section
$accountPanel = <<< END
<a href="user.account.php">My Account</a> | <a href="index.php?logout=true">Log Out</a>
END;
?>