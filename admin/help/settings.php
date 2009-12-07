<?
if($_GET['topic'] == "metaTags"){
echo <<<END
Metatags are used to help search engines find your page. Enter descriptive terms for the entire site, comma-separated. <a href="http://help.findsalus.com/?topic=settings.metaTags" target="_blank">[?]</a>
END;
}

if($_GET['topic'] == "shortTitle"){
echo <<<END
A shorter title for your site, used throughout the administration section and optionally various places in the site itself. Example: <em>XYZ Inc.</em>. <a href="http://help.findsalus.com/?topic=settings.shortTitle" target="_blank">[?]</a>
END;
}

if($_GET['topic'] == "longTitle"){
echo <<<END
The full title of your website. Example: <em>Company XYZ Incorporated</em>. <a href="http://help.findsalus.com/?topic=settings.longTitle" target="_blank">[?]</a>
END;
}

if($_GET['topic'] == "adminEmail"){
echo <<<END
The site administrators' e-mail address, for use on official site mailings. <a href="http://help.findsalus.com/?topic=settings.adminEmail" target="_blank">[?]</a>
END;
}

if($_GET['topic'] == "siteUrl"){
echo <<<END
The base URL for your website, without a trailing slash. Usually <code>http://www.mywebsite.com</code>. <a href="http://help.findsalus.com/?topic=settings.siteUrl" target="_blank">[?]</a>
END;
}

if($_GET['topic'] == "adminUrl"){
echo <<<END
The location of the administration section of your site, without a trailing slash. Typically <code>http://www.mywebsite.com/admin</code>. <a href="http://help.findsalus.com/?topic=settings.adminUrl" target="_blank">[?]</a>
END;
}
?>