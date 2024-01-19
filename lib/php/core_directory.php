<?php
class Directorys
{
	var $fileList;
	var $dirName;
	function __construct($dirName)
	{
		$this->dirName=$dirName;
		$this->fileList=array();
	}
	function getFileList()
	{
		return $this->_getFileList($this->dirName);
	}
	function _getFileList($dirPath)
	{
		//print($dirPath);
		if ($handle = opendir($dirPath)) 
		{
			$temp = array();
			$i=0;
			while ($file = readdir($handle)) 
			{
				if($file != "." && $file!="..")
				{
					$name = $dirPath."/".$file;
					$temp = array();
					$temp['name']=$file;
					$temp['path']=$name;
					
					if(is_dir($name))
					{
						$temp['type']='FOLDER';
						$this->_getFileList($name);
					}
					else
					{
						$temp['type']='FILE';
						$this->fileList[count($this->fileList)]=$temp;
					}
				}
			}
			closedir($handle);
			return $this->fileList;
		}
	}
}
?>