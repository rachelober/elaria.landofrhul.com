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

/* If you want to force the script to use files, set the follow value to true */
$use_files = false;

global $MDB, $DB, $version_number;
$version_number = "3.3";

if (!$use_files)
{
	/* Attempt mysql, on failure use file database */
	require_once("../config_mysql.php");
	$temp_cn = @mysql_connect($DB['host'],$DB['username'],$DB['password']);
	$temp_db = @mysql_select_db($DB['name'], $temp_cn);
}
else
{
	/* get something to trigger next if else statement */
	$temp_cn = false;
}

if($temp_cn && $temp_db)
{
	mysql_close($temp_cn);
	require_once("mysql_db.php");
}
else
{
	unset($DB);
	require_once("file_db.php");
} 

$MDB = new Main_Database();

class Stats_Total
{
	var $hits;
	var $visits;
	var $online=0;
	var $days;

	var $average_hits;
	var $average_visits;

	var $big_day_visits=0;
	var $big_day_date="N/A";

	function Get_Averages()
	{
		/* Use simple equation to get averages */
		$this->average_hits = floor($this->hits / $this->days);
		$this->average_visits = floor($this->visits / $this->days);
	}

	function Get_Online_Visits($onlineusers)
	{
		for($i=0; $i<count($onlineusers); $i++)
		{
			if($onlineusers[$i]->online)
			{
				$this->online++;
			}
		}
	}

	function Get_Big_Day($daynum, $allstats)
	{
		$this->big_day_visits = $allstats[$daynum]->visits;
		$this->big_day_date = $allstats[$daynum]->date;
	}

	function Get_Rise_Fall_Rate($new_num, $old_num)
	{
		/* Calculate rise / fall rate */
		if ($new_num > $old_num)
		{
			$rate_return = ($new_num - $old_num) / $old_num;
		}
		else
		{
			$rate_return = (($old_num - $new_num) / $new_num) * -1;
		}

		return $rate_return;
	}
}

class Stats_User
{
	var $ip;
	var $hits;
	var $visits;
	var $online;
	var $time;
	var $date;

	function Get_Online()
	{
		if($this->time > time() - (20*60) && $this->ip != "localhost")
		{
			$this->online = true;
		}
	}
}

class Stats_Day
{
	var $hits;
	var $visits;
	var $time;
	var $date;
}

function Main()
{
	global $MDB;

	$MDB->GetAllStats($All_Stats, $Todays_Stats, $Total_Stats);

	/* Sort out data based on time */
	Sort_Data($All_Stats);
	Sort_Data($Todays_Stats);

	/* Get and prepare some needed info for features */
	$bigdaynum = Get_Big_Day($All_Stats);
	$Total_Stats->Get_Big_Day($bigdaynum, $All_Stats);
	$Total_Stats->Get_Averages();
	Get_Todays_Dates($Todays_Stats);
	$Total_Stats->Get_Online_Visits($Todays_Stats);

	/* Display Information */
	Show_Header();
	Display_Information($Total_Stats);
	echo "<br><b>Current Totals:</b>";
	Display_Summary($Total_Stats);
	echo "<br><br><b>Last 7 Days</b><br>";
	Display_Day_Graph($All_Stats, count($All_Stats)-7, 7);
	echo "<br><br><b>Last 30 Days</b>";
	Display_Day_Graph($All_Stats, count($All_Stats)-30, 30);
	echo "<br><br><b>This month:</b>";
	Show_Month(date('m'), date('Y'), 1);
	echo "<br><br><b>Most Recent Visitors</b>:";
	Display_Recent_Visitors($Todays_Stats);
	echo "<br><br><b>Other Stats</b>:<br>";
	Display_Archive_Links($All_Stats);
	Show_Footer();
}

function Show_Month($month, $year, $ignore=0)
{
	global $MDB;

	$MDB->GetAllStats($All_Stats, $Todays_Stats, $Total_Stats);

	/* Sort out data based on time */
	Sort_Data($All_Stats);
	Sort_Data($Todays_Stats);

	$oldtime = mktime(0, 0, 0, $month, 1, $year);
	$j=0;

	for($i=0; $i<count($All_Stats); $i++)
	{
		if (date("m-Y", $All_Stats[$i]->time) == $month."-".$year)
		{
			$This_Month[$j] = $All_Stats[$i];
			$j++;
		}
	}

	$days_logged = $j;

	$tdatestr = date("mY", $oldtime);

	/* Display Information */
	Show_Header();
	if(!$ignore)
		echo "Displaying Information for ".date("F \o\f Y", $oldtime)."<br>";
	Display_Month_Summary($This_Month, 0, $days_logged);
	echo "<br>";
	Display_Day_Graph($This_Month, 0, $days_logged);
	echo "<br>";
	if(!$ignore)
	{
		Display_Archive_Links($All_Stats);
		Show_Footer();
	}

}

function Show_Header()
{
	global $version_number;
	?>
	<html>
	<head>
	<title>EP-Dev Counter Stats</title>
	</head>
	<body>
	<?
		if($_GET['update'])
		{
			$version_update = Get_Version_Num($version_number);
			if ($version_update['new'])
			{
				echo "<font color=\"red\"><b>Your current version, ".$version_number.", is not up-to-date. It is recommended you update now by visiting EP-Dev.com</b></font>";
			}
			else
			{
				echo "<b>Your Counter is up-to-date.</b>";
			}
		}
}

function Show_Footer()
{
	global $version_number;
	?>
	<center>Visit <a href="http://www.ep-dev.com" target="_blank">www.ep-dev.com</a>!<br>
	<?
			echo "EP-Dev Counter Version ".$version_number." (<a href=\"index.php?update=1\">check for update</a>)";
	?>
	</center>
	</body>
	</html>
	<?

}

function Display_Archive_Links($All_Stats)
{
	$Logged_Months = array();
	$j=0;
	for ($i=0; $i<count($All_Stats); $i++)
	{
		if (!in_array("month=".date("m", $All_Stats[$i]->time)."&year=".date("Y", $All_Stats[$i]->time), $Logged_Months))
		{
			$Logged_Months[$j] = "month=".date("m", $All_Stats[$i]->time)."&year=".date("Y", $All_Stats[$i]->time);
			$Logged_Months2[$j] = date("F, Y", $All_Stats[$i]->time);
			$j++;
		}
	}
	?>
	<form name="jumpmenu">
	<select name="menu" onChange="location=document.jumpmenu.menu.options[document.jumpmenu.menu.selectedIndex].value;" value="GO">
	<?
		echo "<option value=\"index.php\"".(!isset($_GET['dm']) ? " SELECTED" : "").">Current Stats</option>\n";
	for($i=count($Logged_Months)-1; $i>=0; $i--)
	{
		echo "<option value=\"index.php?p=show&".$Logged_Months[$i]."&dm=".$i."\"".(isset($_GET['dm']) && $_GET['dm'] == $i ? " SELECTED" : "").">".$Logged_Months2[$i]."</option>\n";
	}
	?>
	</select>
	</form>
	<?
}

function Display_Recent_Visitors($Todays_Stats)
{
	?>
	<table width="75%" border="0" cellpadding="0">
	  <tr> 
		<td><strong>IP Address</strong></td>
		<td><strong>Time</strong></td>
		<td><strong>Recent Hits</strong></td>
		<td><strong>Recent Visits</strong></td>
	  </tr>
	<?

	for($i=0; $i<count($Todays_Stats); $i++)
	{
	?>
	  <tr> 
		<td><? echo $Todays_Stats[$i]->ip; ?></td>
		<td><? echo $Todays_Stats[$i]->date; ?></td>
		<td><? echo $Todays_Stats[$i]->hits; ?></td>
		<td><? echo $Todays_Stats[$i]->visits; ?></td>
	  </tr>
	<?
	}

	?>
	</table>
	<?
}

function Display_Information($Total_Stats)
{
	?>
	<table width="65%" border="0" cellpadding="0">
	  <tr>
		<td><strong>Current Information:</strong></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td width="30%"><strong>Total Days Logged:</strong></td>
		<td width="10%"><? echo $Total_Stats->days; ?></td>
		<td><strong>Biggest Visits Day:</strong></td>
		<td><? echo $Total_Stats->big_day_date." - ".$Total_Stats->big_day_visits; ?> visits</td>
	  </tr>
	</table>
	<?
}

function Display_Summary($Total_Stats)
{
	?>
	<table width="40%" border="0" cellpadding="0">
		  <tr>
			<td>
		<table width="100%" border="0" cellpadding="0">
				<tr> 
				  <td><strong>Summary:</strong></td>
				  <td>&nbsp;</td>
				</tr>
				<tr> 
				  <td>Total Hits:</td>
				  <td><? echo $Total_Stats->hits; ?></td>
				</tr>
				<tr> 
				  <td>Total Visits:</td>
				  <td><? echo $Total_Stats->visits; ?></td>
				</tr>
				<tr> 
				  <td>Currently Online*:</td>
				  <td><? echo $Total_Stats->online; ?></td>
				</tr>
			  </table></td>
			<td><table width="100%" border="0" cellpadding="0">
				<tr> 
				  <td><strong>Averages:</strong></td>
				  <td>&nbsp;</td>
				</tr>
				<tr> 
				  <td>Daily Hits:</td>
				  <td><? echo $Total_Stats->average_hits; ?></td>
				</tr>
				<tr> 
				  <td>Daily Visits:</td>
				  <td><? echo $Total_Stats->average_visits; ?></td>
				</tr>
				<tr> 
				  <td>Hits / Visit</td>
				  <td><? echo floor($Total_Stats->hits / $Total_Stats->visits); ?> hits / visit</td>
				</tr>
			  </table></td>
		  </tr>
	</table>
	<?
}

function Display_Month_Summary($All_Stats, $startkey, $numdays)
{

	for($i=$startkey; $i<$startkey+$numdays+1; $i++)
	{
		$total_hits += $All_Stats[$i]->hits;
		$total_visits += $All_Stats[$i]->visits;
	}

	$average_hits = floor($total_hits / $numdays);
	$average_visits = floor($total_visits / $numdays);

	?>
	<table width="40%" border="0" cellpadding="0">
		  <tr>
			<td>
		<table width="100%" border="0" cellpadding="0">
				<tr> 
				  <td><strong>Summary:</strong></td>
				  <td>&nbsp;</td>
				</tr>
				<tr> 
				  <td>Total Hits:</td>
				  <td><? echo $total_hits; ?></td>
				</tr>
				<tr> 
				  <td>Total Visits:</td>
				  <td><? echo $total_visits; ?></td>
				</tr>
				<tr> 
				  <td>Currently Online*:</td>
				  <td>N/A</td>
				</tr>
			  </table></td>
			<td><table width="100%" border="0" cellpadding="0">
				<tr> 
				  <td><strong>Averages:</strong></td>
				  <td>&nbsp;</td>
				</tr>
				<tr> 
				  <td>Daily Hits:</td>
				  <td><? echo $average_hits; ?></td>
				</tr>
				<tr> 
				  <td>Daily Visits:</td>
				  <td><? echo $average_visits; ?></td>
				</tr>
				<tr> 
				  <td>Hits / Visit:</td>
				  <td><? echo floor($total_hits / $total_visits); ?> hits / visit</td>
				</tr>
			  </table></td>
		  </tr>
	</table>
	<?
}

function Sort_Data(&$data_array)
{
	$array_size = count($data_array);

	/*
		We use a bubble sort to array values from smallest to largest. In our case, we compare
		time values and reorganizing them from oldest to youngest, 0=oldest.
	*/
	for($i=0; $i<$array_size-1; $i++)
	{
		for($j=0; $j<$array_size-1-$i; $j++)
		{
			if ($data_array[$j+1]->time < $data_array[$j]->time)
			{
				$temp = $data_array[$j];
				$data_array[$j] = $data_array[$j+1];
				$data_array[$j+1] = $temp;
			}
		}
	}
}

function Get_Big_Day($allstats)
{
	$i=0;
	$bigday = $i;
	for($i=0; $i<count($allstats)-1; $i++)
	{
		if($allstats[$i+1]->visits > $allstats[$bigday]->visits)
		{
			$bigday = $i+1;
		}
	}
	return $bigday;
}

function Get_Todays_Dates(&$Todays_Stats)
{
	for($i=0; $i<count($Todays_Stats); $i++)
	{
		$Todays_Stats[$i]->date = date("D, g:i A", $Todays_Stats[$i]->time);
		$Todays_Stats[$i]->Get_Online();
	}
}



function Display_Day_Graph($All_Stats, $start, $days)
{
	?>
	<table width="100%" height="225" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
	  <tr>
	  <?
	$largest_num = 0;
	for ($i=$start; $i<($days+$start)-1; $i++)
	{
		if ($All_Stats[$i+1]->hits > $All_Stats[$largest_num]->hits)
		{
			$largest_num = $i+1;
		}
	}

	$largest_num = $All_Stats[$largest_num]->hits;

	/* crunch some numbers to avoid table size problems */
	$div_num = pow(10, strlen($largest_num)-4);
	$div_prop = floor($largest_num / (($largest_num - ($largest_num % $div_num)) / $div_num));
	if($div_prop < 1)
	{
		$div_prop = 1;
	}

	?>
	<td width="50" valign="bottom"><strong><font color="#0099FF">hits</font> /<br><font color="#0033FF">visits</font><br>Count (x<?php echo $div_prop; ?>):</strong></td>
	<?

	$largest_num = (0.2 * $largest_num) + $largest_num;

	for ($i=$start; $i<($days+$start); $i++)
	{
		$toph = 200 - (($All_Stats[$i]->hits / $largest_num) * 200);
		$bottomh = 200 - $toph;
		$visith = ($All_Stats[$i]->visits / ($All_Stats[$i]->hits == 0 ? 1 : $All_Stats[$i]->hits)) * $bottomh;
		$hith = $bottomh - $visith;

		/* Fix large display problems */
		if ($hith > 225)
			$hith = 225;
		if ($visith > 225)
			$visith = 225;

		if ($visith + $hith > 450)
		{
			$hith = (0.70 * 225);
			$visith = (0.30 * 225);
		}
		elseif ($visith + $hith > 225 && $hith = 225)
		{
			$hith = 225 - $visith;
		}
		elseif ($visith + $hith > 225 && $visith = 225)
		{
			$visith = 225 - $hith;
		}
		?>
		<td>
		  <table height="225" border="0" cellpadding="0" cellspacing="0" width="90%">
			<tr>
			  <td valign="top" height="<? echo $toph; ?>" title="<? echo date("F d, Y", $All_Stats[$i]->time); ?>">
			<div align="center"><font size="1"><? echo date("d", $All_Stats[$i]->time); ?></font></div></td>
			</tr>
			<tr>
			  <td valign="bottom" height="<? echo $bottomh; ?>" title="hits: <? echo $All_Stats[$i]->hits; ?> | visits: <? echo $All_Stats[$i]->visits; ?>">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
				  <tr>
					<td bgcolor="#0099FF" height="<? echo $hith; ?>"><img src="pix.gif" width="1" height="1"></td>
				  </tr>
				  <tr>
					<td bgcolor="#0033FF" height="<? echo $visith; ?>"><img src="pix.gif" width="1" height="1"></td>
				  </tr>
				</table>
			  </td>
			</tr>
			<tr>
			  <td height="25" align="center"><font size="1"><font color="#0099FF" title="<? echo $All_Stats[$i]->hits; ?>"><? echo round($All_Stats[$i]->hits / $div_prop); ?></font><br><font color="#0033FF" title="<? echo $All_Stats[$i]->visits; ?>"><? echo round($All_Stats[$i]->visits / $div_prop); ?></font></font></td>
			</tr>
		  </table>
		  </td>
		<?
	}
		?>
	  </tr>
	</table>
	<?
}

function Get_Version_Num($cur_version)
{
	$cur_version = $cur_version;
	$file = fopen ("http://www.ep-dev.com/scripts/version.php?ep-dev-counter", "r");
	if (!$file)
	{
		$version_num = $cur_version;
	}
	else
	{
		$version_num = fgets ($file);
		fclose($file);
	}
	$return['ver'] = $version_num;
	$return['new'] = ($version_num != $cur_version ? true : false);
	return $return;
}

function Go_Error($error)
{
	echo "error: ".$error."<br> or mysql:".mysql_error();
}


switch($_GET['p'])
{
	case "show" : Show_Month($_GET['month'], $_GET['year']);
		break;
	default : Main();
}