<?php
	$dir = new Directorys("thumb/objects/");
	$filelist = $dir->getFilelist();
	
	for($i=0;$i<count($filelist);$i++)
	{
?>
<div class="rightmenuitem"><img  src="<?=$filelist[$i]['path']?>" onclick="updateObjects(this,'<?=$filelist[$i]['name']?>')"  /></div>
<?php
	}
?>