<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `user` (
	`userid` int(11) NOT NULL auto_increment,
	`username` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`firstname` VARCHAR(255) NOT NULL,
	`lastname` VARCHAR(255) NOT NULL,
	`emailaddress` VARCHAR(255) NOT NULL,
	`userlevel` TINYINT NOT NULL, PRIMARY KEY  (`userid`));
*/

/**
* <b>User</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 2.6 / PHP4
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=User&attributeList=array+%28%0A++0+%3D%3E+%27username%27%2C%0A++1+%3D%3E+%27password%27%2C%0A++2+%3D%3E+%27firstName%27%2C%0A++3+%3D%3E+%27lastName%27%2C%0A++4+%3D%3E+%27emailAddress%27%2C%0A++5+%3D%3E+%27userLevel%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++5+%3D%3E+%27TINYINT%27%2C%0A%29
*/
class User
{
	var $userId = '';

	/**
	 * @var VARCHAR(255)
	 */
	var $username;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $password;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $firstName;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $lastName;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $emailAddress;
	
	/**
	 * @var TINYINT
	 */
	var $userLevel;
	
	var $pog_attribute_type = array(
		"userId" => array("NUMERIC", "INT"),
		"username" => array("TEXT", "VARCHAR", "255"),
		"password" => array("TEXT", "VARCHAR", "255"),
		"firstName" => array("TEXT", "VARCHAR", "255"),
		"lastName" => array("TEXT", "VARCHAR", "255"),
		"emailAddress" => array("TEXT", "VARCHAR", "255"),
		"userLevel" => array("NUMERIC", "TINYINT"),
		);
	var $pog_query;
	
	function User($username='', $password='', $firstName='', $lastName='', $emailAddress='', $userLevel='')
	{
		$this->username = $username;
		$this->password = $password;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->emailAddress = $emailAddress;
		$this->userLevel = $userLevel;
	}
	
	
	/**
	* Gets object from database
	* @param integer $userId 
	* @return object $User
	*/
	function Get($userId)
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select * from `user` where `userid`='".intval($userId)."' LIMIT 1";
		$Database->Query($this->pog_query);
		$this->userId = $Database->Result(0, "userid");
		$this->username = $Database->Unescape($Database->Result(0, "username"));
		$this->password = $Database->Unescape($Database->Result(0, "password"));
		$this->firstName = $Database->Unescape($Database->Result(0, "firstname"));
		$this->lastName = $Database->Unescape($Database->Result(0, "lastname"));
		$this->emailAddress = $Database->Unescape($Database->Result(0, "emailaddress"));
		$this->userLevel = $Database->Unescape($Database->Result(0, "userlevel"));
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $userList
	*/
	function GetList($fcv_array, $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' && $sortBy == ''?"LIMIT $limit":'');
		if (sizeof($fcv_array) > 0)
		{
			$userList = Array();
			$Database = new DatabaseConnection();
			$this->pog_query = "select userid from `user` where ";
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
			$this->pog_query .= " order by userid asc $sqlLimit";
			$Database->Query($this->pog_query);
			$thisObjectName = get_class($this);
			for ($i=0; $i < $Database->Rows(); $i++)
			{
				$user = new $thisObjectName();
				$user->Get($Database->Result($i, "userid"));
				$userList[] = $user;
			}
			if ($sortBy != '')
			{
				$f = '';
				$user = new User();
				if (isset($user->pog_attribute_type[$sortBy]) && ($user->pog_attribute_type[$sortBy][0] == "NUMERIC" || $user->pog_attribute_type[$sortBy][0] == "SET"))
				{
					$f = 'return $user1->'.$sortBy.' > $user2->'.$sortBy.';';
				}
				else if (isset($user->pog_attribute_type[$sortBy]))
				{
					$f = 'return strcmp(strtolower($user1->'.$sortBy.'), strtolower($user2->'.$sortBy.'));';
				}
				usort($userList, create_function('$user1, $user2', $f));
				if (!$ascending)
				{
					$userList = array_reverse($userList);
				}
				if ($limit != '')
				{
					$limitParts = explode(',', $limit);
					if (sizeof($limitParts) > 1)
					{
						return array_slice($userList, $limitParts[0], $limitParts[1]);
					}
					else
					{
						return array_slice($userList, 0, $limit);
					}
				}
			}
			return $userList;
		}
		return null;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $userId
	*/
	function Save()
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "select `userid` from `user` where `userid`='".$this->userId."' LIMIT 1";
		$Database->Query($this->pog_query);
		if ($Database->Rows() > 0)
		{
			$this->pog_query = "update `user` set 
			`username`='".$Database->Escape($this->username)."', 
			`password`='".$Database->Escape($this->password)."', 
			`firstname`='".$Database->Escape($this->firstName)."', 
			`lastname`='".$Database->Escape($this->lastName)."', 
			`emailaddress`='".$Database->Escape($this->emailAddress)."', 
			`userlevel`='".$Database->Escape($this->userLevel)."' where `userid`='".$this->userId."'";
		}
		else
		{
			$this->pog_query = "insert into `user` (`username`, `password`, `firstname`, `lastname`, `emailaddress`, `userlevel` ) values (
			'".$Database->Escape($this->username)."', 
			'".$Database->Escape($this->password)."', 
			'".$Database->Escape($this->firstName)."', 
			'".$Database->Escape($this->lastName)."', 
			'".$Database->Escape($this->emailAddress)."', 
			'".$Database->Escape($this->userLevel)."' )";
		}
		$Database->InsertOrUpdate($this->pog_query);
		if ($this->userId == "")
		{
			$this->userId = $Database->GetCurrentId();
		}
		return $this->userId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $userId
	*/
	function SaveNew()
	{
		$this->userId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$Database = new DatabaseConnection();
		$this->pog_query = "delete from `user` where `userid`='".$this->userId."'";
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
			$pog_query = "delete from `user` where ";
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