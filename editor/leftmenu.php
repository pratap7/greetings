<?php
	$dir = new Directorys("thumb/background/");
	$filelist = $dir->getFilelist();
	
	for($i=0;$i<count($filelist);$i++)
	{
?>
<div class="leftmenuitem" style=" display:box;
box-orient:horizontal;"><img  src="<?=$filelist[$i]['path']?>" onclick="updateBackground(this,'<?=$filelist[$i]['name']?>')"  /></div>
<?php
	}
?>