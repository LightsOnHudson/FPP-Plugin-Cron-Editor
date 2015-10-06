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
$output = shell_exec('crontab -l');
$cron_file = "/tmp/crontab.txt";
if(isset($_POST['add_cron'])) {
	if(!empty($_POST['add_cron'])) {
		file_put_contents($cron_file, $output.$_POST['add_cron'].PHP_EOL);

	}



	if(!empty($_POST['remove_cron'])) {
		$remove_cron = str_replace($_POST['remove_cron']."\n", "", $output);
		file_put_contents($cron_file, $remove_cron.PHP_EOL);

	}

	if(isset($_POST['remove_all_cron'])) {
		echo exec("crontab -r");
	} else {
		echo exec("crontab $cron_file");

	}
	$uri = $_SERVER['REQUEST_URI'];
	header("Location: $uri");
	exit;

}
?>
<b>Current Cron Jobs:</b><br>

<?
<?php echo nl2br($output); ?>

<h2>Add or Remove Cron Job</h2>
<form method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
<b>Add New Cron Job:</b><br>
<input type="text" name="add_cron" size="100" placeholder="e.g.: * * * * * /usr/local/bin/php -q /home/username/public_html/my_cron.php"><br>
<b>Remove Cron Job:</b><br>
<input type="text" name="remove_cron" size="100" placeholder="e.g.: * * * * * /usr/local/bin/php -q /home/username/public_html/my_cron.php"><br>
<input type="checkbox" name="remove_all_cron" value="1"> Remove all cron jobs?<br>

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
