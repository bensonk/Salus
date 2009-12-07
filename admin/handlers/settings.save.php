<?
include("../salus.php");

function saveSetting($settingName)
{
	$setting = new Setting();
	$settingList = $setting->GetList(array(array("setting", "=", $settingName)));
	if(sizeof($settingList) > 0){
		$setting->Get($settingList[0]->settingId);
		$setting->value = $_POST[$settingName];
		if(!$setting->Save()){
			$error = true;
		}
	}
	else
	{
		$setting->setting = $settingName;
		$setting->value = $_POST[$settingName];
		if(!$setting->Save()){
			$error = true;
		}
	}
}

if($_SESSION['loggedIn'] == true && $_SESSION['userLevel'] < 2)
{
	//set fields
	saveSetting("shortTitle");
	saveSetting("longTitle");
	saveSetting("metaTags");
	saveSetting("adminEmail");
	saveSetting("adminUrl");
	saveSetting("siteUrl");
	//save record
	if ($error != true)
		echo 'Settings saved successfully.&nbsp;&nbsp;<img src="imgs/icons/disk.gif" alt="Saved" />';
	else
		echo 'There was an error saving your settings.&nbsp;&nbsp;<img src="imgs/icons/error.gif" alt="Error" />';
}
else
{
	echo 'Authentication error!';
}
?>
