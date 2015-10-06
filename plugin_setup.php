<?php
$skipJSsettings = 1;
//include_once '/opt/fpp/www/config.php';
include_once '/opt/fpp/www/common.php';

$pluginName = "CronEditor";

$pluginUpdateFile = $settings['pluginDirectory']."/".$pluginName."/"."pluginUpdate.inc";


include_once 'functions.inc.php';
include_once 'commonFunctions.inc.php';

$myPid = getmypid();

$gitURL = "https://github.com/LightsOnHudson/FPP-Plugin-Cron-Editor.git";

//arg0 is  the program
//arg1 is the first argument in the registration this will be --list
//$DEBUG=true;
$logFile = $settings['logDirectory']."/".$pluginName.".log";
$sequenceExtension = ".fseq";

logEntry("plugin update file: ".$pluginUpdateFile);


$ENABLED=$_POST["ENABLED"];


if($DEBUG) {

	
	
}

$output = shell_exec('crontab -l');

$cronResult = exec('/usr/bin/crontab -l',$outputResult);

logEntry($cronResult[0]);



echo $output;
if(isset($_POST['submit']))
{


	WriteSettingToFile("ENABLED",$ENABLED,$pluginName);
	//WriteSettingToFile("STATION_ID",urlencode($STATION_ID),$pluginName);
	WriteSettingToFile("SEPARATOR",urlencode($SEPARATOR),$pluginName);

} else {

	

	$ENABLED = $pluginSettings['ENABLED'];
	
	$SEPARATOR = urldecode($pluginSettings['SEPARATOR']);
	
	
}

if(isset($_POST['updatePlugin']))
{
	logEntry("updating plugin...");
	$updateResult = updatePluginFromGitHub($gitURL, $branch="master", $pluginName);

	echo $updateResult."<br/> \n";
}

//the library keeps repeating the message. send a clear

$LOOPMESSAGE="YES";
//echo "Enabled: ".$ENABLED."<br/> \n";
?>

<html>
<head>
</head>

<div id="cronEditor" class="settings">
<fieldset>
<legend>Cron Editor Support Instructions</legend>

<p>Known Issues:
<ul>
<li>None known</ul>

<p>Configuration:
<ul>
<li></ul>

<form method="post" action="http://<? echo $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']?>/plugin.php?plugin=<? echo $pluginName;?>&page=plugin_setup.php">
<?php 
echo "ENABLE PLUGIN: ";

if($ENABLED == "on" || $ENABLED == 1) {
		echo "<input type=\"checkbox\" checked name=\"ENABLED\"> \n";
//PrintSettingCheckbox("Radio Station", "ENABLED", $restart = 0, $reboot = 0, "ON", "OFF", $pluginName = $pluginName, $callbackName = "");
	} else {
		echo "<input type=\"checkbox\"  name=\"ENABLED\"> \n";
}


echo "<p/> \n";

?>
<p/>
<input id="submit_button" name="submit" type="submit" class="buttons" value="Save Config">
<p/>
<?

 if(file_exists($pluginUpdateFile))
 {
 	//echo "updating plugin included";
	include $pluginUpdateFile;
}

?>
<p>To report a bug, please file it against <?php echo $gitURL;?>
</form>

</fieldset>
</div>
<br />
</html>
