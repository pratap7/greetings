<?php
class Files
{
	var $file;
	var $filename;
	function __construct($filename)
	{
		$this->filename=$filename;
	}
	function read()
	{
		$this->file = fopen($this->filename,"r");
		return fread($this->file,filesize($this->filename));
		fclose($this->file);
	}
	function write($string)
	{
		$this->file = fopen($this->filename,"w");
		fwrite($this->file,$string,strlen($string));
		fclose($this->file);
	}
	function append($string)
	{
		if(file_exists($this->filename))
		{
		$this->file = fopen($this->filename,"a");
		fwrite($this->file,$string,strlen($string));
		fclose($this->file);
		}
		else
		{
			$this->write($string);
		}
	}
	function delete()
	{
		unlink($this->filename);
	}
}

class Tempfile
{
	var $file;
	function __construct()
	{
		$filename = md5(time());
		$this->file = new Files($filename);
	}
	function read()
	{
		return $this->file->read();
	}
	function write($string)
	{
		$this->file->write($string);
	}
	function delete()
	{
		$this->file->delete();
	}
}
class Logfile
{
	var $file;
	var $echo=false;
	function __construct($logname,$path)
	{
		$filename = $path."/".$logname.date("d-m-Y").".log";
		$this->file = new Files($filename);
	}
	function write($message,$type="INFO")
	{
		$message="$type : ".date("d-m-Y h:i:s")."\n".$message."\n\n\n";
		$this->file->append($message);
		if($this->echo==true){
			echo $message;
		}
	}
	
}
?>