<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `page` (
	`pageid` int(11) NOT NULL auto_increment,
	`shorttitle` VARCHAR(255) NOT NULL,
	`longtitle` VARCHAR(255) NOT NULL,
	`tags` TEXT NOT NULL,
	`content` LONGTEXT NOT NULL,
	`locked` VARCHAR(255) NOT NULL,
	`type` VARCHAR(255) NOT NULL,
	`authorcreated` VARCHAR(255) NOT NULL,
	`authormodified` VARCHAR(255) NOT NULL,
	`datecreated` DATE NOT NULL,
	`datemodified` DATE NOT NULL,
	`sortorder` INT NOT NULL, PRIMARY KEY  (`pageid`));
*/

/**
* <b>Page</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 2.6 / PHP4
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=Page&attributeList=array+%28%0A++0+%3D%3E+%27shortTitle%27%2C%0A++1+%3D%3E+%27longTitle%27%2C%0A++2+%3D%3E+%27tags%27%2C%0A++3+%3D%3E+%27content%27%2C%0A++4+%3D%3E+%27locked%27%2C%0A++5+%3D%3E+%27type%27%2C%0A++6+%3D%3E+%27authorCreated%27%2C%0A++7+%3D%3E+%27authorModified%27%2C%0A++8+%3D%3E+%27dateCreated%27%2C%0A++9+%3D%3E+%27dateModified%27%2C%0A++10+%3D%3E+%27sortOrder%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27TEXT%27%2C%0A++3+%3D%3E+%27LONGTEXT%27%2C%0A++4+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++5+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++6+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++7+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++8+%3D%3E+%27DATE%27%2C%0A++9+%3D%3E+%27DATE%27%2C%0A++10+%3D%3E+%27INT%27%2C%0A%29
*/
class Page
{
	var $pageId = '';

	/**
	 * @var VARCHAR(255)
	 */
	var $shortTitle;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $longTitle;
	
	/**
	 * @var TEXT
	 */
	var $tags;
	
	/**
	 * @var LONGTEXT
	 */
	var $content;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $locked;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $type;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $authorCreated;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $authorModified;
	
	/**
	 * @var DATE
	 */
	var $dateCreated;
	
	/**
	 * @var DATE
	 */
	var $dateModified;
	
	/**
	 * @var INT
	 */
	var $sortOrder;
	
	var $pog_attribute_type = array(
		"pageId" => array("NUMERIC", "INT"),
		"shortTitle" => array("TEXT", "VARCHAR", "255"),
		"longTitle" => array("TEXT", "VARCHAR", "255"),
		"tags" => array("TEXT", "TEXT"),
		"content" => array("TEXT", "LONGTEXT"),
		"locked" => array("TEXT", "VARCHAR", "255"),
		"type" => array("TEXT", "VARCHAR", "255"),
		"authorCreated" => array("TEXT", "VARCHAR", "255"),
		"authorModified" => array("TEXT", "VARCHAR", "255"),
		"dateCreated" => array("NUMERIC", "DATE"),
		"dateModified" => array("NUMERIC", "DATE"),
		"sortOrder" => array("NUMERIC", "INT"),
		);
	var $pog_query;
	
	function Page($shortTitle='', $longTitle='', $tags='', $content='', $locked='', $type='', $authorCreated='', $authorModified='', $dateCreated='', $dateModified='', $sortOrder='')
	{
		$this->shortTitle = $shortTitle;
		$this->longTitle = $longTitle;
		$this->tags = $tags;
		$this->content = $content;
		$this->locked = $locked;
		$this->type = $type;
		$this->authorCreated = $authorCreated;
		$this->authorModified = $authorModified;
		$this->dateCreated = $dateCreated;
		$this->dateModified = $dateModified;
		$this->sortOrder = $sortOrder;
	}
	
	
	/**
	* Gets object from database
	* @param integer $pageId 
	* @return object $Page
	*/
	function Get($pageId)
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select * from `page` where `pageid`='".intval($pageId)."' LIMIT 1";
		$Database->Query($this->pog_query);
		$this->pageId = $Database->Result(0, "pageid");
		$this->shortTitle = $Database->Unescape($Database->Result(0, "shorttitle"));
		$this->longTitle = $Database->Unescape($Database->Result(0, "longtitle"));
		$this->tags = $Database->Unescape($Database->Result(0, "tags"));
		$this->content = $Database->Unescape($Database->Result(0, "content"));
		$this->locked = $Database->Unescape($Database->Result(0, "locked"));
		$this->type = $Database->Unescape($Database->Result(0, "type"));
		$this->authorCreated = $Database->Unescape($Database->Result(0, "authorcreated"));
		$this->authorModified = $Database->Unescape($Database->Result(0, "authormodified"));
		$this->dateCreated = $Database->Result(0, "datecreated");
		$this->dateModified = $Database->Result(0, "datemodified");
		$this->sortOrder = $Database->Unescape($Database->Result(0, "sortorder"));
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $pageList
	*/
	function GetList($fcv_array, $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' && $sortBy == ''?"LIMIT $limit":'');
		if (sizeof($fcv_array) > 0)
		{
			$pageList = Array();
			$Database = new DatabaseConnection();
			$this->pog_query = "select pageid from `page` where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$this->pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
					{
						$this->pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]) && $this->pog_attribute_type[$fcv_array[$i][0]][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]][0] != 'SET')
					{
						$this->pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".$Database->Escape($fcv_array[$i][2])."'";
					}
					else
					{
						$this->pog_query .= "`".strtolower($fcv_array[$i][0])."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
					}
				}
			}
			$this->pog_query .= " order by pageid asc $sqlLimit";
			$Database->Query($this->pog_query);
			$thisObjectName = get_class($this);
			for ($i=0; $i < $Database->Rows(); $i++)
			{
				$page = new $thisObjectName();
				$page->Get($Database->Result($i, "pageid"));
				$pageList[] = $page;
			}
			if ($sortBy != '')
			{
				$f = '';
				$page = new Page();
				if (isset($page->pog_attribute_type[$sortBy]) && ($page->pog_attribute_type[$sortBy][0] == "NUMERIC" || $page->pog_attribute_type[$sortBy][0] == "SET"))
				{
					$f = 'return $page1->'.$sortBy.' > $page2->'.$sortBy.';';
				}
				else if (isset($page->pog_attribute_type[$sortBy]))
				{
					$f = 'return strcmp(strtolower($page1->'.$sortBy.'), strtolower($page2->'.$sortBy.'));';
				}
				usort($pageList, create_function('$page1, $page2', $f));
				if (!$ascending)
				{
					$pageList = array_reverse($pageList);
				}
				if ($limit != '')
				{
					$limitParts = explode(',', $limit);
					if (sizeof($limitParts) > 1)
					{
						return array_slice($pageList, $limitParts[0], $limitParts[1]);
					}
					else
					{
						return array_slice($pageList, 0, $limit);
					}
				}
			}
			return $pageList;
		}
		return null;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $pageId
	*/
	function Save()
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select `pageid` from `page` where `pageid`='".$this->pageId."' LIMIT 1";
		$Database->Query($this->pog_query);
		if ($Database->Rows() > 0)
		{
			$this->pog_query = "update `page` set 
			`shorttitle`='".$Database->Escape($this->shortTitle)."', 
			`longtitle`='".$Database->Escape($this->longTitle)."', 
			`tags`='".$Database->Escape($this->tags)."', 
			`content`='".$Database->Escape($this->content)."', 
			`locked`='".$Database->Escape($this->locked)."', 
			`type`='".$Database->Escape($this->type)."', 
			`authorcreated`='".$Database->Escape($this->authorCreated)."', 
			`authormodified`='".$Database->Escape($this->authorModified)."', 
			`datecreated`='".$this->dateCreated."', 
			`datemodified`='".$this->dateModified."', 
			`sortorder`='".$Database->Escape($this->sortOrder)."' where `pageid`='".$this->pageId."'";
		}
		else
		{
			$this->pog_query = "insert into `page` (`shorttitle`, `longtitle`, `tags`, `content`, `locked`, `type`, `authorcreated`, `authormodified`, `datecreated`, `datemodified`, `sortorder` ) values (
			'".$Database->Escape($this->shortTitle)."', 
			'".$Database->Escape($this->longTitle)."', 
			'".$Database->Escape($this->tags)."', 
			'".$Database->Escape($this->content)."', 
			'".$Database->Escape($this->locked)."', 
			'".$Database->Escape($this->type)."', 
			'".$Database->Escape($this->authorCreated)."', 
			'".$Database->Escape($this->authorModified)."', 
			'".$this->dateCreated."', 
			'".$this->dateModified."', 
			'".$Database->Escape($this->sortOrder)."' )";
		}
		$Database->InsertOrUpdate($this->pog_query);
		if ($this->pageId == "")
		{
			$this->pageId = $Database->GetCurrentId();
		}
		return $this->pageId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $pageId
	*/
	function SaveNew()
	{
		$this->pageId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "delete from `page` where `pageid`='".$this->pageId."'";
		return $Database->Query($this->pog_query);
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array)
	{
		if (sizeof($fcv_array) > 0)
		{
			$Database = new DatabaseConnection();
			$pog_query = "delete from `page` where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) !== 1)
					{
						$pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]) && $this->pog_attribute_type[$fcv_array[$i][0]][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]][0] != 'SET')
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$Database->Escape($fcv_array[$i][2])."'";
					}
					else
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
					}
				}
			}
			return $Database->Query($pog_query);
		}
	}
}
?>