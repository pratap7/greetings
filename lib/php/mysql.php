<?php 
if (!defined('PFX')) {
	if (!empty($parameter['table_prefix'])) {
		define ("PFX",$parameter['table_prefix']);
	} else define ("PFX",'');
}
//set_magic_quotes_runtime(0);
$db_number=0;
$queryFile;
class DB {
    function DB() 
	{
		global $parameter,$cashroot,$queryFile,$databaseLoging;
		$this->host = $parameter['host'];
		$this->db   = $parameter['db'];
		$this->user = $parameter['user'];
		$this->pass = $parameter['pass'];
		$this->link = @mysql_connect($this->host, $this->user, $this->pass);
		if (!$this->link) die(db_down());
		$this->version = mysql_get_server_info();;
		if (!$this->link) {
			$GLOBALS['connected'] = false;
		} else $GLOBALS['connected'] = true;
		@mysql_select_db($this->db) or die("Error : ".mysql_error());
		$version = $this->version;
		if ( isset($parameter['dbcharset']) && (intval($version[0]) >= 5 || preg_match('#^4\.[1-9]#',$version)) )
			mysql_query("SET NAMES ". $parameter['dbcharset']);
		if($databaseLoging=="true")
		{
			$queryFile=fopen($cashroot."/query.txt","w");
		}
		
    }
}
	function clone_dbname()
	{
		global $config;
		$c=count($config);
		$db=-1;
		for($i=0;$i<$c;$i++)
			{
				if($config[$i]['type']=='clone')
				{
					$db=$i;
				}
			}
		return $db;
	}

	function open_db($dbc)
	{
		global $DB,$config,$parameter,$db_number;
		$db_number=$dbc;
		if(count($config)>$dbc)
		{
			
			$parameter['db'] = $config[$dbc]['db'];
			$parameter['user'] = $config[$dbc]['user'];
			$parameter['pass'] = $config[$dbc]['pass'];
			if($config[$dbc]['port']=="")
			{
				$parameter['host'] = $config[$dbc]['server'];
			}
			else
			{
				$parameter['host'] = $config[$dbc]['server'].":".$config[$dbc]['port'];
			}
			
			$DB = new DB;
		}
		else
		{
			print("Database opening failed. DB_CONFIG is invalid.");
		}
	}
	function change_db($dbx)
	{
		db_close();
		open_db($dbx);
	}
//-------------------------------------------------------------
	function writeQueryMap($q,$t)
	{
		global $DB,$parameter, $qcount, $qtime, $production_status,$cashroot,$queryFile;
		fwrite($queryFile,"$t\t\t$q\n");
	}
	function safe_query($q='',$debug='',$unbuf='')
	{
		global $DB,$parameter, $qcount, $qtime, $production_status,$cashroot,$databaseLoging;
		$method = (!$unbuf) ? 'mysql_query' : 'mysql_unbuffered_query';
		if (!$q) return false;
		
		$start = getmicrotime();
		$result = $method($q,$DB->link);
		$time = sprintf('%02.6f', getmicrotime() - $start);
		if($databaseLoging=="true")
		{
			writeQueryMap($q,$time);
		}
		@$qtime += $time;
		@$qcount++;
		if ($result === false and (@$production_status == 'debug' or @$production_status == 'test'))
			trigger_error(mysql_error() . n . $q, E_USER_ERROR);

		//trace_add("[SQL ($time): $q]");

		if(!$result) return false;
		return $result;
	}
	function redo_file()
	{
		global $db_number,$config;
		$server = $_SERVER['ORIG_PATH_INFO'];
		$file = $config[$db_number]['redolog'];
		if($file=="")
		{
			return '';
		}
		$s=explode("/",$server);
		$f=count($s)-2;
		$fx=$file;
		for($i=0;$i<$f;$i++)
			{
				$fx="../".$fx;
				
			}
//		print($fx);
		return $fx;
	}
	function redo_log($q)
	{
		$q=$q."\n";
		$redo=redo_file();
		
		if($redo!='')
		{
			$ifile = fopen($redo.".inx","a") or die("Error loging redo index");
			fwrite($ifile,filesize($redo.".log")."-".strlen($q)."\n");
			fclose($ifile);
			
			$rfile = fopen($redo.".log","a") or die("Error loging redo file");
			fwrite($rfile,$q);
			fclose($rfile);			
		}		
	}
// -------------------------------------------------------------
	function safe_delete($q, $debug='')
	{
		global $db_number;
		redo_log($q);
		if ($r = safe_query($q,$debug)) {
			$cdb=clone_dbname();
			if($cdb!=-1)
			{
				$d=$db_number;
				change_db($cdb);
				safe_query($q,$debug);
				change_db($d);
			}
			return true;
		}
		return false;
	}

// -------------------------------------------------------------
	function safe_update($q,$debug='') 
	{
		global $db_number;
		redo_log($q);
		if ($r = safe_query($q,$debug)) {
			$cdb=clone_dbname();
			if($cdb!=-1)
			{
				$d=$db_number;
				change_db($cdb);
				safe_query($q,$debug);
				change_db($d);
			}			
			return true;
		}
		return false;
	}

// -------------------------------------------------------------
	function safe_insert($q,$debug='') 
	{
		global $DB;
		global $db_number;
		redo_log($q);
		if ($r = safe_query($q,$debug)) {
			$id = mysql_insert_id($DB->link);
			$cdb=clone_dbname();
			if($cdb!=-1)
			{
				$d=$db_number;
				change_db($cdb);
				safe_query($q,$debug);
				change_db($d);
			}			
			return ($id === 0 ? true : $id);
		}
		return false;
	}
	function safe_table($q,&$row,&$col,$debug='')
	{
		global $db_number;
		//$c=count_record($q);
		$out = array();
		$r = safe_query($q,$debug);
		if ($r)
		{
			$num = mysql_num_rows($r);
			for($i=0;$i<$num;$i++)
				{
					$a=mysql_fetch_row($r);
					array_push($out,$a);
				}
				$col = count($out[0]);
				$row = count($out);

				return $out;
		}
		
		else
		{
			//print("error in ".$q."<br>");
		/*	$col=0;
			$row=0;
			
			$t=safe_table("select * from mails where condition = 'WEAR'",$r,$c);
			for($i=0;$i<$r;$i++)
				{
					$f_add=mail_address($t[$i][9],"",1);
					$t_add=mail_address($t[$i][10],"",1);
					$subject=$t[$i][2];
					$mess=$t[$i][3];
					$p=$q;
					$date_time=date_time();
					$mess=str_replace("<prg:past_page>",$p,$t[$i][3]);
					$mess=str_replace("<prg:mail_time>",$date_time,$mess);
					create_mail($f_add,$t_add,$subject,$mess);
					
				}			
		*/	return false;
		}
		
		/*
		}
		else
		{
			return false;
		}
		*/
	}
	
	function count_record($q)
	{
		$r = safe_query($q,'');
		if($r==false)
		{
			return 0;
		}
		$n=mysql_num_rows($r);// or die($q);
		return $n;
	}

	function db_close()
	{
		global $DB,$queryFile,$databaseLoging;
		mysql_close($DB->link);
		if($databaseLoging=="true")
		{
			fclose($queryFile);
		}
	}
	function db_down() 
	{
		header('Status: 503 Service Unavailable');
		$error = mysql_error();
		return <<<eod
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Untitled</title>
</head>
<body>
<p align="center" style="margin-top:4em">Database unavailable.</p>
<!-- $error -->
</body>
</html>
eod;
	}
	
open_db(0);
?>
