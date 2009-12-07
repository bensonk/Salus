<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `setting` (
	`settingid` int(11) NOT NULL auto_increment,
	`setting` VARCHAR(255) NOT NULL,
	`value` TEXT NOT NULL, PRIMARY KEY  (`settingid`));
*/

/**
* <b>Setting</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 2.6 / PHP4
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=Setting&attributeList=array+%28%0A++0+%3D%3E+%27setting%27%2C%0A++1+%3D%3E+%27value%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27TEXT%27%2C%0A%29
*/
class Setting
{
	var $settingId = '';

	/**
	 * @var VARCHAR(255)
	 */
	var $setting;
	
	/**
	 * @var TEXT
	 */
	var $value;
	
	var $pog_attribute_type = array(
		"settingId" => array("NUMERIC", "INT"),
		"setting" => array("TEXT", "VARCHAR", "255"),
		"value" => array("TEXT", "TEXT"),
		);
	var $pog_query;
	
	function Setting($setting='', $value='')
	{
		$this->setting = $setting;
		$this->value = $value;
	}
	
	
	/**
	* Gets object from database
	* @param integer $settingId 
	* @return object $Setting
	*/
	function Get($settingId)
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select * from `setting` where `settingid`='".intval($settingId)."' LIMIT 1";
		$Database->Query($this->pog_query);
		$this->settingId = $Database->Result(0, "settingid");
		$this->setting = $Database->Unescape($Database->Result(0, "setting"));
		$this->value = $Database->Unescape($Database->Result(0, "value"));
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $settingList
	*/
	function GetList($fcv_array, $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' && $sortBy == ''?"LIMIT $limit":'');
		if (sizeof($fcv_array) > 0)
		{
			$settingList = Array();
			$Database = new DatabaseConnection();
			$this->pog_query = "select settingid from `setting` where ";
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
			$this->pog_query .= " order by settingid asc $sqlLimit";
			$Database->Query($this->pog_query);
			$thisObjectName = get_class($this);
			for ($i=0; $i < $Database->Rows(); $i++)
			{
				$setting = new $thisObjectName();
				$setting->Get($Database->Result($i, "settingid"));
				$settingList[] = $setting;
			}
			if ($sortBy != '')
			{
				$f = '';
				$setting = new Setting();
				if (isset($setting->pog_attribute_type[$sortBy]) && ($setting->pog_attribute_type[$sortBy][0] == "NUMERIC" || $setting->pog_attribute_type[$sortBy][0] == "SET"))
				{
					$f = 'return $setting1->'.$sortBy.' > $setting2->'.$sortBy.';';
				}
				else if (isset($setting->pog_attribute_type[$sortBy]))
				{
					$f = 'return strcmp(strtolower($setting1->'.$sortBy.'), strtolower($setting2->'.$sortBy.'));';
				}
				usort($settingList, create_function('$setting1, $setting2', $f));
				if (!$ascending)
				{
					$settingList = array_reverse($settingList);
				}
				if ($limit != '')
				{
					$limitParts = explode(',', $limit);
					if (sizeof($limitParts) > 1)
					{
						return array_slice($settingList, $limitParts[0], $limitParts[1]);
					}
					else
					{
						return array_slice($settingList, 0, $limit);
					}
				}
			}
			return $settingList;
		}
		return null;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $settingId
	*/
	function Save()
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select `settingid` from `setting` where `settingid`='".$this->settingId."' LIMIT 1";
		$Database->Query($this->pog_query);
		if ($Database->Rows() > 0)
		{
			$this->pog_query = "update `setting` set 
			`setting`='".$Database->Escape($this->setting)."', 
			`value`='".$Database->Escape($this->value)."' where `settingid`='".$this->settingId."'";
		}
		else
		{
			$this->pog_query = "insert into `setting` (`setting`, `value` ) values (
			'".$Database->Escape($this->setting)."', 
			'".$Database->Escape($this->value)."' )";
		}
		$Database->InsertOrUpdate($this->pog_query);
		if ($this->settingId == "")
		{
			$this->settingId = $Database->GetCurrentId();
		}
		return $this->settingId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $settingId
	*/
	function SaveNew()
	{
		$this->settingId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "delete from `setting` where `settingid`='".$this->settingId."'";
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
			$pog_query = "delete from `setting` where ";
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