<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
*
* This setup file does the following:
* 1. Checks if configuration file is present
* 2. Checks if the data in the configuration file is correct
* 3. Checks if the database and table exist
* 4. Create table if not present
* 5. Tests 5 CRUD functions and determine if everything is OK for all objects within the current directory
* 6. When all tests pass, provides an interface to the database and a way to manage objects.
*/
if(file_exists("../configuration.php"))
{
	include_once("../configuration.php");
}
include_once("setup_library/authentication.php");
include_once("setup_library/setup_misc.php");
include_once("data_initialization/read_dump_lib.php");
if(!isset($_SESSION['diagnosticsSuccessful']) || (isset($_GET['step']) && $_GET['step']=="diagnostics"))
{
	$_SESSION['diagnosticsSuccessful'] = false;
}
?>
<?php include "setup_library/inc.header.php";?>
<form action="./index.php" method="POST">
<?php
ini_set("max_execution_time", 0);
if(count($_POST) > 0 && $_SESSION['diagnosticsSuccessful']==false)
{
?>
<div class="container">
<div class="left">
	<div class="logo2"></div>
	<div class="text"><div class="gold">POG setup diagnostics</div>
	<br/>Setup performs unit tests on all your objects in the object directory and makes sure they're OK. <br/>This makes sure that your objects can talk to your database correctly. This can also be useful if you modify / customize the objects manually and want to make sure they still work once you're done.
	<br/><br/>The diagnostics screen on the right shows the results of those tests. If all tests pass successfully, you can be assured that all objects are working correctly.
	</div>
</div>
<div class="middle">
	<div id="tabs">
		<a href="./index.php?step=diagnostics"><img src="./setup_images/tab_setup.gif"/></a>
		<img src="./setup_images/tab_separator.gif"/>
		<img src="./setup_images/tab_diagnosticresults_on.gif"/>
		<img src="./setup_images/tab_separator.gif"/>
		<img src="./setup_images/tab_manageobjects.gif"/>
	</div><div class="subtabs">&nbsp;</div><a href="./index.php?step=diagnostics"><img src="./setup_images/setup_recheck.jpg" border="0"/></a><div class="middle2">
<?php
	$errors = 0;
	AddTrace('Initializing POG Setup....OK!');
	if (isset($GLOBALS['configuration']['pdoDriver']))
	{
		$errors++;
		AddError('POG setup for PHP4 objects cannot be run with a PDO configuration file. Regenerate configuration.php');
	}
	else
	{
		/**
		 * verify file structure status
		 */
		if(!file_exists("../objects/class.database.php"))
		{
			$errors++;
			AddError('Database wrapper (class.database.php) is missing.');
		}
		else
		{
			include "../objects/class.database.php";
		}
		if (!file_exists("../configuration.php"))
		{
			$errors++;
			AddError('Configuration file (configuration.php) is missing');
		}

		$dir = opendir('../objects/');
		$objects = array();
		while(($file = readdir($dir)) !== false)
		{
			if(strlen($file) > 4 && substr(strtolower($file), strlen($file) - 4) === '.php' && !is_dir($file) && $file != "class.database.php")
			{
				$objects[] = $file;
				include_once("../objects/{$file}");
			}
		}
		closedir($dir);
		if (sizeof($objects) == 0)
		{
			$errors++;
			AddError("[objects] folder does not contain any POG object.");
		}

		/**
		 * verify configuration info
		 */
		if ($errors == 0)
		{
			AddTrace('File Structure....OK!');
			if (!mysql_connect ($GLOBALS['configuration']['host'].":".$GLOBALS['configuration']['port'], $GLOBALS['configuration']['user'], $GLOBALS['configuration']['pass']))
			{
				$errors++;
				AddError('Cannot connect to the specified database server. Edit configuration.php');
			}
			if ($errors == 0)
			{
				if (!mysql_select_db ($GLOBALS['configuration']['db']))
				{
					$errors++;
					AddError('Cannot find the specified database "'.$GLOBALS['configuration']['db'].'". Edit configuration.php');
				}
			}
		}

		/**
		 * verify storage status
		 */

		if ($errors == 0)
		{
			AddTrace("Configuration Info....OK!\n");
			AddTrace("Storage Status");
			foreach($objects as $object)
			{
				$objectName = GetObjectName("../objects/".$object);
				eval ('$instance = new '.$objectName.'();');
				if (TestStorageExists($objectName, "mysql"))
				{
					if (isset($_POST['pog_table']) && ($_POST['pog_table'] == "recreate" || $_POST['pog_table'] == "recreate_import"))
					{
						if (!TestDeleteStorage($instance))
						{
							$errors++;
							AddError("Dropping table '".strtolower($objectName)."' failed. Drop and recreate the table manually.");
						}
						else
						{
							if (!TestCreateStorage("../objects/".$object))
							{
								$errors++;
								AddError("Creating table [".strtolower($objectName)."] failed. Create the table manually using the generated SQL query in the object header.");
							}
							else
							{
								AddTrace("\tDropping & Recreating table [".strtolower($objectName)."]....OK!");
							}
						}
					}
					else
					{
						if (!TestAlterStorage($instance))
						{
							$errors++;
							AddError("Aligning [$objectName] with table '".strtolower($objectName)."' failed. Alter the table manually so that object attributes and table columns match.");
						}
						else
						{
							AddTrace("\tAligning [$objectName] with table '".strtolower($objectName)."'....OK!");
						}
					}
				}
				else
				{
					if (!TestCreateStorage("../objects/".$object))
					{
						$errors++;
						AddError("Creating table [".strtolower($objectName)."] failed. Create the table manually using the generated SQL query in the object header.");
					}
					else
					{
						AddTrace("\tCreating table [".strtolower($objectName)."]....OK!");
					}
				}
			}
		}

		$objectNameList = array();

		/**
		 * Initialize test data?
		 */
		if (isset($_POST['pog_table']) && $_POST['pog_table'] == 'recreate_import')
		{
			$initialData = file_get_contents('data_initialization/data_initialization.sql');
			PMA_splitSqlFile($statements, $initialData, 4);
			if (sizeof($statements) > 0)
			{
				foreach ($statements as $statement)
				{
					if (!TestExecuteQuery($statement['query']))

					{
						$errors++;
						AddError('Statement "'.$statement['query'].'" failed');
					}
				}
			}
		}


		/**
		 * verify object status
		 */
		if (isset($_POST['pog_test']) && $_POST['pog_test'] == 'no')
		{
			$objectNameList = array();
			foreach($objects as $object)
			{
				$objectName = GetObjectName("../objects/".$object);
				if (isset($objectName))
				{
					$objectNameList[] = $objectName;
				}
			}
		}

		if ($errors == 0 && isset($_POST['pog_test']) && $_POST['pog_test'] == 'yes')
		{
			AddTrace("\nPOG Essentials");

			$_SESSION['links'] = array();

			$objectCount = 1;
			foreach($objects as $object)
			{
				$objectName = GetObjectName("../objects/".$object);
				if (isset($objectName))
				{
					eval('$instance = new '.$objectName.'();');
					AddTrace("\t[".$objectName."]");
					$objectNameList[] = $objectName;

					$link = GetAtLink("../objects/".$object);
					$_SESSION['links'][$objectName] = $link;

					if (!TestEssentials($instance))
					{
						$errors++;
						AddError("Object $objectName failed essential tests");
					}
					if ($objectCount != sizeof($objects))
					{
						AddTrace("\t***");
					}
				}
				$objectCount++;
			}
		}


		if ($errors == 0 && isset($_POST['pog_test']) && $_POST['pog_test'] == 'yes')
		{
			AddTrace("\nPOG Relations PreRequisites");
			$objectCount = 1;
			foreach ($objects as $object)
			{
				$objectName = GetObjectName("../objects/".$object);
				if (isset($objectName))
				{
					eval('$instance = new '.$objectName.'();');
					AddTrace("\t[".$objectName."]");
					if (!TestRelationsPreRequisites($instance, $objectNameList, $objectName))
					{
						$errors++;
					}
					if ($objectCount != sizeof($objects))
					{
						AddTrace("\t***");
					}
				}
				$objectCount++;
			}
		}


		if ($errors == 0 && isset($_POST['pog_test']) && $_POST['pog_test'] == 'yes')
		{
			AddTrace("\nPOG Relations");
			$objectCount = 1;
			foreach ($objects as $object)
			{
				$objectName = GetObjectName("../objects/".$object);
				if (isset($objectName))
				{
					eval('$instance = new '.$objectName.'();');
					AddTrace("\t[".$objectName."]");
					if (!TestRelations($instance, $objectNameList))
					{
						$errors++;
						AddError("Object $objectName failed relations tests");
					}
					if ($objectCount != sizeof($objects))
					{
						AddTrace("\t***");
					}
				}
				$objectCount++;
			}
		}
		if ($errors == 0)
		{
			$_SESSION['diagnosticsSuccessful'] = true;
		}
		if(isset($_POST['pog_test']) && $_POST['pog_test'] == 'no')
		{
			AddTrace("\nUNIT TESTS NOT PERFORMED. FOUND $errors ERROR(S)");
		}
		else
		{
			AddTrace("\nCHECKED ".count($objectNameList)." OBJECT(S). FOUND $errors ERROR(S)".($errors == 0 ? ". HURRAY!" : ":"));
		}
		AddTrace("---------------------------------------------------");
		if (isset($_SESSION['errorMessages']))
		{
			$errorMessages = unserialize($_SESSION['errorMessages']);
		}
		$traceMessages = unserialize($_SESSION['traceMessages']);
		$diagnostics = '';
		foreach ($traceMessages as $traceMessage)
		{
			$diagnostics .= "\n$traceMessage";
		}
		if (isset($errorMessages))
		{
			foreach ($errorMessages as $errorMessage)
			{
				$diagnostics .= "\n$errorMessage\n";
			}
		}
		$_SESSION['fileNames'] = serialize($objects);
		$_SESSION['objectNameList'] = serialize($objectNameList);
	}
	echo "<textarea>".$diagnostics."</textarea><br/><br/><br/></div>";
	if ($_SESSION['diagnosticsSuccessful'])
	{
		echo '<input type="image" src="./setup_images/setup_proceed.gif" name="submit"/>';
	}
	unset($_POST, $instanceId, $_SESSION['traceMessages'], $_SESSION['errorMessages']);
?>
</div></div>
<?php
}
else if($_SESSION['diagnosticsSuccessful'] == true)
{
?>
<div class="container">
	<div class="left">
		<div class="logo3"></div>
		<div class="text"><div class="gold">POG documentation summary</div>
		<br/><br/>The following 3 documents summarize what POG is all about:<br/><br/>
		1. <a href="http://www.phpobjectgenerator.com/plog/file_download/15">POG Essentials</a><br/><br/>
		2. <a href="http://www.phpobjectgenerator.com/plog/file_download/21">POG Object Relations</a><br/><br/>
		3. <a href="http://www.phpobjectgenerator.com/plog/file_download/18">POG SOAP API</a>
		</div><!--text-->
	</div><!--left-->
<div class="middle33">
	<div id="tabs3">
		<a href="./index.php?step=diagnostics"><img src="./setup_images/tab_setup.gif"/></a>
		<img src="./setup_images/tab_separator.gif"/>
		<img src="./setup_images/tab_diagnosticresults.gif"/>
		<img src="./setup_images/tab_separator.gif"/>
		<a href="./index.php"><img src="./setup_images/tab_manageobjects_on.gif"/></a>
	</div><!--tabs3--><div class="subtabs">
<?php
	//provide interface to the database
	include "./setup_library/xPandMenu.php";
	$root = new XMenu();
	if(file_exists("configuration.php"))
	{
		include "../configuration.php";
	}
	if(file_exists("../objects/class.database.php"))
	{
		include "../objects/class.database.php";
	}

	$fileNames = unserialize($_SESSION['fileNames']);
	foreach($fileNames as $filename)
	{
		include_once("../objects/{$filename}");
	}
	$objectNameList = unserialize($_SESSION['objectNameList']);
	if (isset($_GET['objectName']))
	{
		$_SESSION['objectName'] = $_GET['objectName'];
	}
	$objectName = (isset($_SESSION['objectName'])?$_SESSION['objectName']:$objectNameList[0]);

	?>
	<div id="header">
  	<ul>
  	<li id='inactive'>My Objects:</li>
	<?php
	if (!isset($_SESSION['objectName']))
	{
		$_SESSION['objectName'] = $objectNameList[0];
	}
	for($i=0; $i<count($objectNameList); $i++)
	{
		$name = $objectNameList[$i];
		eval('$instance = new '.$name.'();');
		if (!TestIsMapping($instance))
		{
			echo "<li ".($_SESSION['objectName']==$objectNameList[$i]?"id='current'":'')."><a href='./index.php?objectName=".$objectNameList[$i]."'>".$objectNameList[$i]."</a></li>";
			//echo "<a href='./index.php?objectName=".$objectNameList[$i]."'".(isset($_SESSION['objectName']) && $_SESSION['objectName']==$objectNameList[$i]?"class='activetab'":(!isset($_SESSION['objectName'])&&$i==0?"class='activetab'":"inactivetab")).">".$objectNameList[$i]."</a> ";
		}
	}
	?>
	</ul>
	</div><!--header-->
	</div><!--subtabs-->
	<div class="toolbar"><a href="<?php echo $_SESSION['links'][$_SESSION['objectName']]?>" target="_blank" title="modify and regenerate object"><img src="./setup_images/setup_regenerate.jpg" border="0"/></a><a href="#" title="Delete all objects" onclick="if (confirm('Are you sure you want to delete all objects in this table? Press OK to Delete.')){window.location='./?thrashall=true';}else{alert('Phew, nothing was deleted ;)');}"><img src='./setup_images/setup_deleteall.jpg' alt='delete all' border="0"/></a><a href="#" onclick="javascript:expandAll();return false;" title="expand all nodes"><img src='./setup_images/setup_expandall.jpg' alt='expand all' border="0"/></a><a href="#" onclick="javascript:collapseAll();return false;" title="collapse all nodes"><img src='./setup_images/setup_collapseall.jpg' alt='collapse all' border="0"/></a><a href="#" title="update all objects to newest POG version" onclick="if (confirm('Setup will now attempt to upgrade your objects by contacting the POG SOAP server. Would you like to continue?')){window.location='./setup_library/upgrade.php';}else{alert('Upgrade aborted');}"><img src='./setup_images/setup_updateall.jpg' alt='update all objects' border='0'/></a></div><div class="middle3">
	<?php
	//is there an action to perform?
	if (isset($_GET['thrashall']))
	{
		eval('$instance = new '.$objectName.'();');
		$instanceId = strtolower(get_class($instance))."Id";
		$instanceList = $instance->GetList(array(array($instanceId, ">", "0")));
		foreach ($instanceList as $instance)
		{
			$instance->Delete();
		}
		unset($_GET);
	}
	echo '<div id="container"></div>';
	$_SESSION['fileNames'] = serialize($fileNames);
	$_SESSION['objectNameList'] = serialize($objectNameList);
?>
<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
</div><!--middle3-->
</div><!--middle33-->
</div><!--container-->
<?php
echo "<script>sndReq('GetList', '', '$objectName', '', '', '', '$objectName');</script>";
}
else
{
	unset($_SESSION['objectNameList'], $_SESSION['fileNames'], $_SESSION['links']);
	//welcome screen
?>
<div class="container">
	<div class="left">
		<div class="logo"></div>
		<div class="text"><div class="gold">What is POG Setup?</div>POG Setup is an extension of the online Php Object Generator. It is meant to help the veteran POG user and the novice alike.
		<br/><br/>POG Setup is a 3 step process which:<br/><br/>
		1. Creates tables for your generated objects.<br/><br/>
		2. Performs diagnostics tests on all objects within your 'objects' directory.<br/><br/>
		3. Provides a light interface to your object tables.</div>
	</div>
	<div class="middle">
		<div id="tabs">
			<img src="./setup_images/tab_setup_on.gif" height="20px" width="70px"/>
			<img src="./setup_images/tab_separator.gif" height="20px" width="17px"/>
			<img src="./setup_images/tab_diagnosticresults.gif" height="20px" width="137px"/>
			<img src="./setup_images/tab_separator.gif" height="20px" width="17px"/>
			<img src="./setup_images/tab_manageobjects.gif" height="20px" width="129px"/>
		</div>
		<div id="nifty">
			<div style="height:500px">
			<img src="./setup_images/setup_welcome.jpg" height="47px" width="617px"/>
			<div class="col1"><img src="./setup_images/pog_setup_closed.jpg"/><div class="gold">What is POG?</div>POG generates PHP objects with integrated CRUD methods to dramatically accelerate web application development in PHP. <br/>
			<br/>POG allows developers to easily map object attributes onto columns of a database table without having to write SQL queries.</div>
			<div class="col2"><img src="./setup_images/pog_setup_open.jpg"/><div class="gold">What is POG Setup?</div>You've generated one or more objects using Php Object Generator ... Now what?<br/>
			<br/>POG SETUP is an answer to this question and takes the POG experience one step further. The Setup process automates <b>table creation</b>, <b>unit testing</b> and provides a light <b>scaffolding</b> environment.</div>
			<div class="col3">
			<div class="gold">If you are ready to get POG'd up, click on thebutton below to proceed. Doing this will:</div><br/>
			<table>
			<tr>
			<td>TABLES:</td>
			<td>
			<select class="ss" name="pog_table">
				<option value="align">Align tables with objects (default)</option>
				<option value="recreate">Recreate tables</option>
				<option value="recreate_import">Recreate tables and initialize data</option>
			</select>
			</td>
			</tr>
			<tr>
			<td>TESTS:</td>
			<td>
			<select class="ss" name="pog_test">
				<option value="yes">Perform unit tests (default)</option>
				<option value="no">Bypass unit tests</option>
			</select>
			</td>
			</tr>
			</table><br/>
			<br/><input type="image" onclick="PleaseWait('');" src="./setup_images/setup_pogmeup.gif" name="submit"/>
			<div align="center" id="pleasewait" style="display:none;"><img src="./setup_images/loading.gif"/></div>
			</div>
			</div>
			<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
		</div>
	</div>
</div>
<?php
}
?>
</form>
<div class="footer">
<?php include "setup_library/inc.footer.php";?>
</div>
</body>
</html>
