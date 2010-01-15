<?php
// --------------------------------------------
// | The EP-Dev Counter script        
// |                                           
// | Copyright (c) 2002-2003 EP-Dev.com :           
// | This program is distributed as free       
// | software under the GNU General Public     
// | License as published by the Free Software 
// | Foundation. You may freely redistribute     
// | and/or modify this program.               
// |                                           
// --------------------------------------------

// ------------- //
// Database Type //
// ------------- //
// Use MySQL Database (1 = use mysql, 0 = use files)
$DB['type'] = 1;


// ----------------- //
// SITE CONFIG SETUP //
// ----------------- //
/* Absolute path to counter script (ex: "/mywebsite/counter/") with trailing slash.
	NOTE: If you do not know what your absolute path is, please run run abs.php that
	is included with this counter's zip file. */
$OPTION['Absolute_Path'] = "/home/ofbcuoud/www/elaria/counter/";


// --------------- //
// COUNTER OPTIONS //
// --------------- //
// Online Time - Number of minutes a visitor is considered online:
$OPTION['OnlineTime'] = 15;

/* Recent Mode - "Today's visitors" - Set to 1 to reset daily, or number or hours (2-23).
	NOTE: If archiving = 1, it is recommended that this is set to 1. */
$OPTION['RecentMode'] = 1;

// Visit Length - Sets how long a "visit" is considered. 0 = once per trim/archive time, 1 = daily.
$OPTION['VisitLength'] = 1;

// Time Database - Sets how often to trim database (weeks) (does not apply if archiving):
$OPTION['TrimDatabase'] = 1;

// Archive Hits/Visits - 1 = archive hits/visits, 0 = do not keep archives.
$OPTION['ArchiveStats'] = 1;

// Debug Mode - Set to 1 to see errors, 0 to hide errors (when possible):
$OPTION['DebugMode'] = 1;

// Hits Offset - Number of hits to be added to total, useful if you are replacing a counter (EX: 1000).
$OPTION['Hits_Offset'] = 0;

// Visits Offset - Does the same thing as hits offset (above), but with the visits count.
$OPTION['Visits_Offset'] = 0;

// IP ignore - enter any IPs you would like to ignore for counter: array("123.123.123.123", "456.456.456.456")
$OPTION['IP_Ignore'] = array("");

// IP block - enter any IPs you would like to block from your page: array("123.123.123.123", "456.456.456.456")
$OPTION['IP_Block'] = array("");


// --------------- //
// COUNTER DISPLAY //
// --------------- //
// Graphics - 1 = Graphical counter, 0 = text counter:
$OPTION['Graphical'] = 0;

// Image Directory - directory to counter images (URL or relative path)... with trailing slash:
$OPTION['Images_Dir'] = "images/default/";

// Image Extension - the extension of your counter graphics (such as .gif or .jpg):
$OPTION['Image_ext'] = ".jpg"; 

// The number of positions to show on counter (EX: 8 spaces with only 500 hits would show up as: 00000500):
$OPTION['Counter_Spaces'] = 8; 

// Add number notation (1 = "1,000"; 2 = "1 000"; 0 = "1000").
$OPTION['Add_Char'] = 1;


// --------------- //
// COUNTER ACCESS  //
// --------------- //
// Hidden Counter - Set to 1 to hide counter, 0 to display (admin access: counter.php?p=stats)
$OPTION['Hide_Counter'] = 0;

// --------------------------------------------
// DO NOT EDIT BELOW THIS LINE                   
// --------------------------------------------
$version_number = 3.3;
ignore_user_abort(true);

/* Correct absolute path (if not found, guess relative path ;) */
if (!file_exists($OPTION['Absolute_Path']."counter.php"))
{
	if (file_exists("/counter/counter.php"))
	{
		$OPTION['Absolute_Path'] = "/counter/";
	}
	else
	{
		Go_Error("bad_absolute_path");
	}
}

if ($DB['type'])
{
	require($OPTION['Absolute_Path']."classes/mysqldb.php");
}
else
{
	require($OPTION['Absolute_Path']."classes/filedb.php");
}

$USR = new User_Information();
$USR->ip = getenv("REMOTE_ADDR");
$USR->otime = time();
$USR->self = true;
$USR->Get_Online();

$MDB = new Main_Database();

class User_Information
{
	var $ip;
	var $otime;
	var $trim = false;
	var $self = false;
	var $online = false;
	var $recent = false;

	var $hits;
	var $visits;

	function Get_Online()
	{
		global $OPTION;

		if (!$this->self)
		{
			/* Determine if visit is recent */
			if (time() - $this->otime < ($OPTION['RecentMode'] > 1 ? ($OPTION['RecentMode'] * 3600) : ((date("d", $this->otime) == date("d") ? 86400 : 0))))
			{
				$this->recent = true;
			}

			/* Determine if visit is online */
			if (time() - $this->otime < $OPTION['OnlineTime'] * 60)
			{
				$this->online = true;
			}
		}
		else
		{
			/* Automatically assume true if self */
			$this->recent = true;
			$this->online = true;
		}
	}

	function Update_Counts()
	{
		global $OPTION;

		/* 
			The next statement adds security, making 
			sure that it only updates current visitor.
		*/
		if ($this->self)
		{
			if (time() - $this->otime > ($OPTION['VisitLength'] > 0 ? $OPTION['VisitLength'] * 86400 : time()))
				$this->visits++;

			$this->hits++;
		}
	}

	function Determine_Trim()
	{
		global $OPTION;

		/*
			Checks if time is older than trim database time, which defaults to 1 day if
			ArchiveStats is set.
		*/
		if ($this->otime < time() - (86400 * ($OPTION['ArchiveStats'] ? 1 : (7 * $OPTION['TrimDatabase']))))
			$this->trim = true;
	}

}

class TotalCounts
{
	var $online=0;
	var $recent=0;
	var $visits=0;
	var $hits=0;

	var $count_array;
	var $count_array2;

	function CreateArray()
	{
		$this->count_array = array($this->online, $this->recent, $this->visits, $this->hits);
	}

	function DismantleArray()
	{
		$this->online = $this->count_array[0];
		$this->recent = $this->count_array[1];
		$this->visits = $this->count_array[2];
		$this->hits = $this->count_array[3];
	}

	function Format()
	{
		global $OPTION;

		$this->CreateArray();

		/* Format images accordingly */
		if($OPTION['Graphical'])
		{
			$this->Format_to_Images();
		}
		elseif($OPTION['Add_Char'])
		{
			$this->Format_Numbers();
		}

		$this->DismantleArray();
	}

	function Format_to_Images()
	{
		global $OPTION;

		$this->count_orig = $this->count_array;

		// Attempt to fix image url if broken by default
		if (!file_exists($OPTION['Images_Dir'].'0'.$OPTION['Image_ext']))
			$OPTION['Images_Dir'] = $OPTION['Absolute_Path'].$OPTION['Images_Dir'];

		$CounterImage=getimagesize($OPTION['Images_Dir'].'0'.$OPTION['Image_ext'])
					or die(Go_Error("image_404"));

		for ($i=0; $i<4; $i++)
		{
			for ($j=0; $j<10; $j++)
				$this->count_array[$i] = str_replace($j, "~~".$j."~~", $this->count_array[$i]);

			for ($j=0; $j<10; $j++)
				$this->count_array[$i] = str_replace("~~".$j."~~", "<img src=\"".$OPTION['Images_Dir'].$j.$OPTION['Image_ext']."\">", $this->count_array[$i]);

			for ($j=0; $j<($OPTION['Counter_Spaces']-strlen($this->count_orig[$i])); $j++)
				$this->count_array[$i] = "<img src=\"".$OPTION['Images_Dir'].'0'.$OPTION['Image_ext']."\">".$this->count_array[$i];

			$this->count_array[$i]=str_replace(">", $CounterImage[3].">", $this->count_array[$i]);
		}
	}

	function Format_Numbers()
	{
		global $OPTION;

		if($OPTION['Add_Char'] == 1)
		{
			for($i=0; $i<4; $i++)
				$this->count_array[$i] = number_format($this->count_array[$i]);
		}
		elseif($OPTION['Add_Char'] == 2)
		{
			for($i=0; $i<4; $i++)
				$this->count_array[$i] = number_format($this->count_array[$i], 2, ',', ' ');
		}
	}

	function Display()
	{
		global $OPTION;

		$file = $OPTION['Absolute_Path']."counter-display.html";
		$display_config = fopen($file, 'rb')
			or die("Counter absolute path incorrect!");

		$display = fread($display_config, filesize($file));
		fclose($display_config);
		$display = ereg_replace("!COUNTER-ONPAGE!", strval($this->online), $display);
		$display = ereg_replace("!COUNTER-TODAY!", strval($this->recent), $display);
		$display = ereg_replace("!COUNTER-VISITS!", strval($this->visits), $display);
		$display = ereg_replace("!COUNTER-HITS!", strval($this->hits), $display);

		echo $display;
	}

}

function Main()
{
	global $USR, $OPTION;

	/* Perform old IP block Feature with javascript */
	if (in_array($USR->ip, $OPTION['IP_Block']))
	{
		?>
		<script language="javascript" type="text/javascript">
			window.location="http://www.yahoo.com";
		</script>
		<?
	}

	/* Perform old IP Ignore Feature */
	if (!in_array($USR->ip, $OPTION['IP_Ignore']))
	{
		User_Update();
	}
	$All_Users = Get_Counters();
	Start_Trim($All_Users);
	if (!$OPTION['Hide_Counter'])
	{
		$CNT = Tally_Counts($All_Users);
		$CNT->Format();
		$CNT->Display();
	}
}

function User_Update()
{
	global $MDB, $USR;

	$cUser = $MDB->Get_User_Data($USR->ip);

	if ($cUser['newvisit'])
	{
		$MDB->Insert_Into_Database($USR->ip, time());
	}
	else
	{
		/* If not a new visit, pull old hit/visit/time info and update accordingly */
		$USR->hits = $cUser['hits'];
		$USR->visits = $cUser['visits'];
		$USR->otime = $cUser['oldtime'];
		$USR->Update_Counts();
		$MDB->Update_Database_Entry($USR->ip, $USR->hits, $USR->visits, time());
	}
}

function Get_Counters()
{
	global $MDB;

	return $MDB->Get_All_Users();
}

function Tally_Counts($All_Users)
{
	global $OPTION;

	$counter = new TotalCounts();

	for($i=0; $i<count($All_Users); $i++)
	{
		if($All_Users[$i]->online)
		{
			$counter->online++;
		}

		if($All_Users[$i]->recent)
		{
			$counter->recent++;
		}

		$counter->hits += $All_Users[$i]->hits;
		$counter->visits += $All_Users[$i]->visits;
	}

	$counter->hits += $OPTION['Hits_Offset'];
	$counter->visits += $OPTION['Visits_Offset'];

	return $counter;
}

function Start_Trim($All_Users)
{
	global $MDB, $OPTION;

	if($OPTION['TrimDatabase'])
	{
		$j=0;
		for($i=0; $i<count($All_Users); $i++)
		{
			if($All_Users[$i]->trim)
			{
				$Users_to_Trim[$j] = $All_Users[$i];
				$j++;
			}
		}

		if($j)
			$MDB->Trim_Database($Users_to_Trim);
	}
}

function Go_Error($error)
{
	global $OPTION, $MDB;
	
	if (file_exists($OPTION['Absolute_Path']."counter-error.php"))
	{	
		if ($OPTION['DebugMode'])
		{
			include($OPTION['Absolute_Path']."counter-error.php");
			ignore_user_abort(false);
			exit();
		}
	}
	elseif ($error = "bad_absolute_path")
	{
		echo "<b>COUNTER ERROR: Bad Absolute Path</b><br>Your absolute path value is incorrectly set in counter.php. To discover your absolute path, run the file abs.php that was packaged in the counter's zip file. After modifying counter.php refresh this page.";
		exit();
	}
	else
	{
		echo "<b>COUNTER ERROR: </b><br>The counter has an error. However, the error file could not be found to provide additional assistance. It is likely that your \"absolute path\" is not correctly set in counter.php or the counter's error file is missing.";
		exit();
	}
}

switch ($_GET['p'])
{
	case "stats": $OPTION['Hide_Counter'] = 0;
				  Main();
		break;
	case "main": Main();
		break;
	case "time" : Main();
				$shorten = microtime(); 
				print ("<br>Page took ".number_format($shorten,3)); 
				print (" seconds.<br><small>EP-Dev Counter version ".number_format($version, 1)."</small>"); 
		break;
	default : Main();
}

/* Final Cleanup */
ignore_user_abort(false);