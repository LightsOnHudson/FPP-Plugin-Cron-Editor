<?php
//$DEBUG=true;
//include_once "/opt/fpp/www/common.php";

$pluginName = "CronEditor";
include_once "functions.inc.php";
include_once "commonFunctions.inc.php";
include "config/config.inc";

$pluginUpdateFile = $settings['pluginDirectory']."/".$pluginName."/"."pluginUpdate.inc";
$logFile = $settings['logDirectory']."/".$pluginName.".log";
$myPid = getmypid();

$gitURL = "https://github.com/LightsOnHudson/FPP-Plugin-Cron-Editor.git";

logEntry("plugin update file: ".$pluginUpdateFile);

$DEBUG = false;

$IP=trim($_POST["IP"]);
$PORT=trim($_POST["PORT"]);
$STATION_ID=trim($_POST["STATION_ID"]);
$DEVICE=trim($_POST["DEVICE"]);
$DEVICE_CONNECTION_TYPE=trim($_POST["DEVICE_CONNECTION_TYPE"]);
$COLOR=trim($_POST["COLOR"]);
$STATIC_TEXT_POST=trim($_POST["STATIC_TEXT_POST"]);
$STATIC_TEXT_PRE=trim($_POST["STATIC_TEXT_PRE"]);
$ENABLED=$_POST["ENABLED"];
$LOOPMESSAGE=$_POST["LOOPMESSAGE"];
$LOOPTIME=$_POST["LOOPTIME"];
$SEPARATOR = $_POST["SEPARATOR"];

if($DEBUG) {

	echo "PORT: ".$_POST['PORT'];//print_r($_POST["PORT"]);
	echo "loop message: ".$_POST["LOOPMESSAGE"]."<br/> \n";
	
}

$betaBriteSequencePATH  = $settings['sequenceDirectory'];

//createBetaBriteSequenceFiles();

if(isset($_POST['submit']))
{

//	echo "Writring config fie <br/> \n";

	WriteSettingToFile("DEVICE",$DEVICE,$pluginName);
	WriteSettingToFile("DEVICE_CONNECTION_TYPE",$DEVICE_CONNECTION_TYPE,$pluginName);
	WriteSettingToFile("IP",$IP,$pluginName);
	WriteSettingToFile("PORT",$PORT,$pluginName);
	WriteSettingToFile("LOOPMESSAGE",$LOOPMESSAGE,$pluginName);
	WriteSettingToFile("COLOR",$COLOR,$pluginName);
	WriteSettingToFile("LOOPTIME",$LOOPTIME,$pluginName);
	WriteSettingToFile("STATIC_TEXT_PRE",urlencode($STATIC_TEXT_PRE),$pluginName);
	WriteSettingToFile("STATIC_TEXT_POST",urlencode($STATIC_TEXT_POST),$pluginName);
	WriteSettingToFile("ENABLED",$ENABLED,$pluginName);
	//WriteSettingToFile("STATION_ID",urlencode($STATION_ID),$pluginName);
	WriteSettingToFile("SEPARATOR",urlencode($SEPARATOR),$pluginName);

} else {

	
	//$STATION_ID = $pluginSettings['STATION_ID'];
	$DEVICE = $pluginSettings['DEVICE'];
	$DEVICE_CONNECTION_TYPE = $pluginSettings['DEVICE_CONNECTION_TYPE'];
	$IP = $pluginSettings['IP'];
	$PORT = $pluginSettings['PORT'];
	$LOOPMESSAGE = $pluginSettings['LOOPMESSAGE'];
	$COLOR = $pluginSettings['COLOR'];
	$STATIC_TEXT_PRE = urldecode($pluginSettings['STATIC_TEXT_PRE']);
	$STATIC_TEXT_POST = urldecode($pluginSettings['STATIC_TEXT_POST']);
	$ENABLED = $pluginSettings['ENABLED'];
	$LOOPTIME = $pluginSettings['LOOPTIME'];
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

<div id="beta" class="settings">
<fieldset>
<legend>BetaBrite Support Instructions</legend>

<p>Known Issues:
<ul>
<li>None known</ul>

<p>Configuration:
<ul>
<li>Configure your connection type, Serial, Static text you want to send in front of Artist and song and post text, loop time if you want looping and color</li>
</ul>

<form method="post" action="http://<? echo $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']?>/plugin.php?plugin=BetaBrite&page=plugin_setup.php">
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
<!--  
Manually Set Station ID<br>
<p><label for="STATION_ID">Station ID:</label>
<input type="text" value="<? if($STATION_ID !="" ) { echo $STATION_ID; } else { echo "";};?>" name="STATION_ID" id="STATION_ID"></input>
(Expected format: up to 8 characters)
</p>
-->
<?

echo "Connection type: \n";

echo "<select name=\"DEVICE_CONNECTION_TYPE\"> \n";
                        if($DEVICE_CONNECTION_TYPE != "")
                        {
				switch ($DEVICE_CONNECTION_TYPE)
				{
					case "SERIAL":
                                		echo "<option selected value=\"".$DEVICE_CONNECTION_TYPE."\">".$DEVICE_CONNECTION_TYPE."</option> \n";
                                //		echo "<option value=\"IP\">IP</option> \n";
                                		break;
					case "IP":
                                		echo "<option selected value=\"".$DEVICE_CONNECTION_TYPE."\">".$DEVICE_CONNECTION_TYPE."</option> \n";
                                		echo "<option value=\"SERIAL\">SERIAL</option> \n";
                        			break;
			
				
	
				}
	
			} else {

                                echo "<option value=\"SERIAL\">SERIAL</option> \n";
                          //      echo "<option value=\"IP\">IP</option> \n";
			}
                
        
echo "</select> \n";
echo "<p/> \n";

echo "<p/> \n";
echo "SERIAL DEVICE: \n";
echo "<select name=\"DEVICE\"> \n";
        foreach(scandir("/dev/") as $fileName)
        {
                if (preg_match("/^ttyUSB[0-9]+/", $fileName)) {
			if($DEVICE == $filename)
			{
                        	echo "<option selected value=\"".$fileName."\">".$fileName."</option> \n";
			} else {
                       		echo "<option value=\"".$fileName."\">".$fileName."</option> \n";
			}
                }
        }
echo "</select> \n";
?>

<p/>
<!--  
IP: 
<input type="text" value="<? if($IP !="" ) { echo $IP; } else { echo "";}?>" name="IP" id="IP"></input>

<p/>

PORT:
<input type="text" value="<? if($PORT !="" ) { echo $PORT; } else { echo "";}?>" name="PORT" id="PORT"></input>

<p/>
-->
STATIC TEXT PRE:
<input type="text" size="64" value="<? if($STATIC_TEXT_PRE !="" ) { echo $STATIC_TEXT_PRE; } else { echo "";}?>" name="STATIC_TEXT_PRE" id="STATIC_TEXT_PRE"></input>


<p/>

STATIC TEXT POST:
<input type="text" size="64" value="<? if($STATIC_TEXT_POST !="" ) { echo $STATIC_TEXT_POST; } else { echo "";}?>" name="STATIC_TEXT_POST" id="STATIC_TEXT_POST"></input>

<p/>
<!-- 
LOOP time (in secs):
<input type="text" value="<? if($LOOPTIME !="" ) { echo $LOOPTIME; } else { echo "10";}?>" name="LOOPTIME" id="LOOPTIME"></input>


<p/>

LOOP:
<?
echo "<select name=\"LOOPMESSAGE\"> \n";

		switch ($LOOPMESSAGE) {

			case "YES":
				echo "<option selected value=\"".$LOOPMESSAGE."\">".$LOOPMESSAGE."</option> \n";
                echo "<option value=\"NO\">NO</option> \n";
            	break;

			case "NO":
				echo "<option selected value=\"".$LOOPMESSAGE."\">".$LOOPMESSAGE."</option> \n";
                echo "<option value=\"YES\">YES</option> \n";
                break;
                
			default:
                  echo "<option value=\"NO\">NO</option> \n";
                  echo "<option value=\"YES\">YES</option> \n";
				break;
				}
                
        
echo "</select> \n";
?>
-->
<p/>
<!--  
COLOR:
-->
<?

//create an array of color here
//echo "<select name=\"COLOR\"> \n";
 //                     echo "<option value=\"YELLOW\">YELLOW</option> \n";
  //                    echo "<option value=\"GREEN\">GREEN</option> \n";
   //                   echo "<option value=\"RAINBOW\">RAINBOW</option> \n";


//echo "</select> \n";


?>


<p/>

Separator between SongTitle & Song Artist:
<input type="text" value="<? if($SEPARATOR !="" ) { echo $SEPARATOR; } else { echo "-";}?>" name="SEPARATOR" id="SEPARATOR"></input>

<p/>
<input id="submit_button" name="submit" type="submit" class="buttons" value="Save Config">


<p>To report a bug, please file it against the BetaBrite plugin project on Git: https://github.com/LightsOnHudson/FPP-Plugin-BetaBrite

<p>
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
