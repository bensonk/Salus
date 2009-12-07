<?
$setting = new Setting();
$settingsList = $setting->GetList(array(array("settingId", ">", 0)));
foreach ($settingsList as $setting)
{
	$settingName = $setting->setting;
	$settingValue = $setting->value;
	$$settingName = $settingValue;
}
?>
