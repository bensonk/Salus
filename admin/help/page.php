<?
if($_GET['topic'] == "tags"){
echo <<<END
<h3>Tags</h3>
<p>Tags are used for everything from describing pages to defining customized sub-menus to setting page properties. Enter tags, comma-separated (<em>e.g.</em> <code>mytag, foo, bar</code>), for any descriptive terms for the page, include possibling search terms and words you may want to use to group pages. Terms applying to the site as a whole are set in the Setting section, and don't need to be included on a per-page basis. <a href="http://help.findsalus.com/?topic=edit.tags" target="_blank">More...</a></p>
END;
}

if($_GET['topic'] == "shortTitle"){
echo <<<END
<h3>Menu Title</h3>
<p>The way the page title will appear on menus and in page titles. Try to keep it as short and to the point as possible&mdash;<q>About</q> instead of <q>About My Organization</q>. <a href="http://help.findsalus.com/?topic=edit.shortTitle" target="_blank">More...</a></p>
END;
}

if($_GET['topic'] == "longTitle"){
echo <<<END
<h3>Page Title</h3>
<p>A longer title for use on page headings&mdash;<q>About My Organization</q> or <q>Product XYZ</q>. This should be roughly 2-8 words; more descriptive language will help improve search engine ranking. <a href="http://help.findsalus.com/?topic=edit.longTitle" target="_blank">More...</a></p>
END;
}

if($_GET['topic'] == "content"){
echo <<<END
<h3>Content</h3>
<p>This is the body of your page. The editor works similarly to familar programs like Microsoft Word. If you'd like to edit the HTML directly, simply press the '&lt;&gt;' button to go into HTML mode. <a href="http://help.findsalus.com/?topic=edit.content" target="_blank">More...</a></p>
END;
}
?>